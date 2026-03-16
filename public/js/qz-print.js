 (function () {
  const POLL_INTERVAL = 2000;
  const LOG_PREFIX = '[QZ]';

  function log(...args) { console.debug(LOG_PREFIX, ...args); }

  async function ensureConnected() {
    if (!window.qz) throw new Error('qz library not loaded');
    if (qz.websocket.isActive()) return;
    try {
      // connect to QZ Tray local websocket — adjust port if your QZ Tray uses a different one
      await qz.websocket.connect({ host: 'localhost', port: 8182, retries: 5, delay: 1 });
      log('Connected to QZ Tray');
    } catch (err) {
      log('QZ connect failed', err);
      throw err;
    }
  }

  async function pollPending() {
    try {
      const res = await fetch('/admin/print/pending', { credentials: 'same-origin' });
      if (res.status === 204) return null;
      if (!res.ok) throw new Error('HTTP ' + res.status);
      return await res.json();
    } catch (err) {
      log('poll error', err);
      return null;
    }
  }

  async function markComplete(jobId, status = 'done', error = null) {
    try {
      const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
      await fetch(`/admin/print/${jobId}/complete`, {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
        body: JSON.stringify({ status, error }),
      });
    } catch (err) {
      log('complete error', err);
    }
  }

  async function doPrint(job) {
    const content = job.content || '';
    if (!content) throw new Error('Empty print content');

    // Default config; to target a printer use qz.configs.create('Printer Name')
    const config = qz.configs.create();
    const data = [{ type: 'raw', format: 'plain', data: content }];
    return qz.print(config, data);
  }

  // Provide certificate and signature promises to satisfy QZ security flow.
  // For development you can use a placeholder certificate (PEM format). For production,
  // return a real certificate and implement server-side signing as documented by QZ.
  try {
    if (window.qz) {
      qz.security.setCertificatePromise(function () {
        // Replace the string below with your PEM certificate if available
        return Promise.resolve('-----BEGIN CERTIFICATE-----\\nDEV\\n-----END CERTIFICATE-----');
      });

      qz.security.setSignaturePromise(function (toSign) {
        // Development: resolve with an empty signature (no-op). For real signing,
        // call your server to sign `toSign` with a private key and return the signature.
        return function (resolve, reject) {
          resolve();
        };
      });
    }
  } catch (e) {
    log('Failed to set certificate/signature promises', e);
  }

  async function loop() {
    try {
      await ensureConnected().catch(() => {}); // keep looping even if not connected

      const job = await pollPending();
      if (!job) return;

      log('Got job', job.job_id, 'order', job.order_id, 'cash_received', job.cash_received);

      try {
        if (!qz.websocket.isActive()) await ensureConnected();
        await doPrint(job);
        await markComplete(job.job_id, 'done');
        log('Printed job', job.job_id);
      } catch (err) {
        log('Print failed', err);
        await markComplete(job.job_id, 'failed', String(err));
      }
    } catch (err) {
      log('loop fatal', err);
    }
  }

  setInterval(loop, POLL_INTERVAL);
  // run immediately once
  loop();
})();

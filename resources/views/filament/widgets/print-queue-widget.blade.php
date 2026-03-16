<div>
    <p class="text-sm text-gray-500">Print queue polling active.</p>
</div>

@push('scripts')
<script>
(function () {
    const POLL_INTERVAL = 5000;
    const PRINTER_NAME = @json($printerName ?? null);

    async function fetchPending() {
        try {
            const res = await fetch('/admin/print/pending', { credentials: 'same-origin' });
            if (res.status === 204) return null;
            if (!res.ok) throw new Error('Failed to fetch pending');
            return await res.json();
        } catch (e) {
            console.debug('PrintQueue: poll error', e);
            return null;
        }
    }

    async function completeJob(jobId, status = 'done', error = null) {
        try {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            await fetch(`/admin/print/${jobId}/complete`, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({ status: status, error: error })
            });
        } catch (e) {
            console.error('PrintQueue: complete failed', e);
        }
    }

    async function ensureQzConnected() {
        if (!window.qz) return false;
        if (qz.websocket.isActive()) return true;
        try {
            await qz.websocket.connect();
            return true;
        } catch (e) {
            console.debug('qz connect failed', e);
            return false;
        }
    }

    async function poll() {
        const job = await fetchPending();
        if (!job) return;

        const { job_id, order_id, content } = job;
        if (!content) {
            await completeJob(job_id, 'failed', 'no content');
            return;
        }

        const qzReady = await ensureQzConnected();
        if (!qzReady) {
            const w = window.open('', '_blank');
            w.document.write('<pre>' + content.replace(/</g,'&lt;') + '</pre>');
            w.document.close();
            await completeJob(job_id, 'done');
            return;
        }

        try {
            const printer = PRINTER_NAME ? await qz.printers.find(PRINTER_NAME) : await qz.printers.find();
            const config = qz.configs.create(printer);
            const data = [{ type: 'raw', format: 'plain', data: content }];
            await qz.print(config, data);
            console.info('PrintQueue: printed job', job_id);
            await completeJob(job_id, 'done');
        } catch (err) {
            console.error('PrintQueue: print failed', err);
            await completeJob(job_id, 'failed', err.message || 'print error');
        }
    }

    setInterval(poll, POLL_INTERVAL);
})();
</script>
@endpush

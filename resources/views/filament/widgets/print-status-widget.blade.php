<div class="fi-widget">
    <div class="px-4 py-3">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-sm font-medium">QZ Tray Connection</h3>
                <p id="qz-connection" class="text-xs text-gray-500">Checking...</p>
            </div>
            <div>
                <h3 class="text-sm font-medium">Last Print Job</h3>
                <p id="last-job" class="text-xs text-gray-500">
                    @if($lastJob)
                        #{{ $lastJob->id }} — {{ $lastJob->status }} — {{ $lastJob->created_at }}
                    @else
                        No jobs
                    @endif
                </p>
            </div>
        </div>
    </div>

    <script>
        (function () {
            function byId(id){ return document.getElementById(id); }
            const connEl = byId('qz-connection');
            const jobEl = byId('last-job');

            function updateConnection() {
                if (window.qz && qz.websocket && qz.websocket.isActive()) {
                    connEl.textContent = 'Connected';
                    connEl.classList.remove('text-gray-500');
                    connEl.classList.add('text-green-600');
                } else {
                    connEl.textContent = 'Disconnected';
                    connEl.classList.remove('text-green-600');
                    connEl.classList.add('text-gray-500');
                }
            }

            async function fetchLastJob(){
                try{
                    const res = await fetch('/admin/print/last', { credentials: 'same-origin' });
                    if (!res.ok) return;
                    const json = await res.json();
                    if (!json) return;
                    jobEl.textContent = `#${json.job_id} — ${json.status} — ${json.created_at}`;
                }catch(e){ /* ignore */ }
            }

            // update quickly and then poll
            updateConnection();
            fetchLastJob();
            setInterval(updateConnection, 2000);
            setInterval(fetchLastJob, 3000);

            // Listen for custom events if print script emits them (optional)
            window.addEventListener('qz-job-printed', (e) => {
                fetchLastJob();
            });
        })();
    </script>
</div>

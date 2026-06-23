(function () {
    const QUEUE_KEY = 'delux.offlineCollections.v1';
    const collectionForm = document.querySelector('form[data-offline-collection-form]');

    function readQueue() {
        try {
            return JSON.parse(localStorage.getItem(QUEUE_KEY) || '[]');
        } catch (error) {
            return [];
        }
    }

    function writeQueue(queue) {
        localStorage.setItem(QUEUE_KEY, JSON.stringify(queue));
        updateBanner();
    }

    function formToPayload(form) {
        const payload = {};
        new FormData(form).forEach((value, key) => {
            payload[key] = value;
        });
        return payload;
    }

    function queueCollection(form) {
        const action = form.action;
        const queue = readQueue().filter(item => item.action !== action);

        queue.push({
            action,
            method: form.method || 'POST',
            payload: formToPayload(form),
            queuedAt: new Date().toISOString(),
        });

        writeQueue(queue);
        showBanner('Saved offline. It will sync when connection returns.', 'warning');
    }

    function buildBody(payload) {
        const body = new FormData();
        Object.entries(payload).forEach(([key, value]) => body.append(key, value));
        return body;
    }

    async function syncQueue() {
        if (!navigator.onLine) {
            updateBanner();
            return;
        }

        let queue = readQueue();
        if (!queue.length) {
            updateBanner();
            return;
        }

        showBanner(`Syncing ${queue.length} offline collection${queue.length === 1 ? '' : 's'}...`, 'info');

        const remaining = [];

        for (const item of queue) {
            try {
                const response = await fetch(item.action, {
                    method: item.method,
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: buildBody(item.payload),
                    credentials: 'same-origin',
                });

                if (!response.ok) {
                    throw new Error(`Sync failed with status ${response.status}`);
                }
            } catch (error) {
                remaining.push(item);
            }
        }

        writeQueue(remaining);

        if (remaining.length) {
            showBanner(`${remaining.length} collection${remaining.length === 1 ? '' : 's'} still pending. Keep this device logged in and try again online.`, 'warning');
            return;
        }

        showBanner('Offline collections synced.', 'success');
        setTimeout(updateBanner, 3000);
    }

    function prefetchCollectionPages() {
        if (!navigator.onLine) return;

        document.querySelectorAll('a[href*="/employee/collection/"]').forEach(link => {
            fetch(link.href, {
                method: 'GET',
                credentials: 'same-origin',
            }).catch(() => {});
        });
    }

    function ensureBanner() {
        let banner = document.getElementById('offlineCollectionBanner');
        if (banner) return banner;

        banner = document.createElement('div');
        banner.id = 'offlineCollectionBanner';
        banner.style.cssText = [
            'position:fixed',
            'left:16px',
            'right:16px',
            'bottom:calc(76px + env(safe-area-inset-bottom))',
            'z-index:9999',
            'display:none',
            'padding:12px 14px',
            'border-radius:12px',
            'font:600 13px/1.35 Inter, system-ui, sans-serif',
            'box-shadow:0 10px 28px rgba(15,23,42,.22)',
            'text-align:center',
        ].join(';');

        document.body.appendChild(banner);
        return banner;
    }

    function showBanner(message, tone) {
        const banner = ensureBanner();
        const styles = {
            info: ['#0f172a', '#e0f2fe', '#38bdf8'],
            success: ['#052e16', '#dcfce7', '#22c55e'],
            warning: ['#451a03', '#ffedd5', '#fb923c'],
        }[tone] || ['#0f172a', '#e2e8f0', '#94a3b8'];

        banner.textContent = message;
        banner.style.color = styles[0];
        banner.style.background = styles[1];
        banner.style.border = `1px solid ${styles[2]}`;
        banner.style.display = 'block';
    }

    function updateBanner() {
        const banner = ensureBanner();
        const pending = readQueue().length;

        if (!navigator.onLine) {
            showBanner(pending ? `${pending} collection${pending === 1 ? '' : 's'} waiting to sync.` : 'Offline mode. Collection entries will be saved on this device.', 'warning');
            return;
        }

        if (pending) {
            showBanner(`${pending} offline collection${pending === 1 ? '' : 's'} pending sync.`, 'info');
            return;
        }

        banner.style.display = 'none';
    }

    if (collectionForm) {
        collectionForm.addEventListener('submit', event => {
            if (navigator.onLine) return;

            event.preventDefault();
            queueCollection(collectionForm);
        });
    }

    window.addEventListener('online', syncQueue);
    window.addEventListener('offline', updateBanner);
    window.addEventListener('load', () => {
        updateBanner();
        syncQueue();
        prefetchCollectionPages();
    });
})();

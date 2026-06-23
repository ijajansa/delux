(function () {
    const QUEUE_KEY = 'delux.offlineForms.v1';
    const LEGACY_COLLECTION_QUEUE_KEY = 'delux.offlineCollections.v1';
    const offlineForms = document.querySelectorAll('form[data-offline-form]');

    migrateLegacyQueue();

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

    function migrateLegacyQueue() {
        if (localStorage.getItem(QUEUE_KEY) || !localStorage.getItem(LEGACY_COLLECTION_QUEUE_KEY)) {
            return;
        }

        try {
            const legacyQueue = JSON.parse(localStorage.getItem(LEGACY_COLLECTION_QUEUE_KEY) || '[]');
            const migratedQueue = legacyQueue.map(item => ({
                ...item,
                label: 'collection',
                replaceKey: item.action,
            }));

            localStorage.setItem(QUEUE_KEY, JSON.stringify(migratedQueue));
            localStorage.removeItem(LEGACY_COLLECTION_QUEUE_KEY);
        } catch (error) {
            localStorage.removeItem(LEGACY_COLLECTION_QUEUE_KEY);
        }
    }

    function formToPayload(form) {
        const payload = {};
        new FormData(form).forEach((value, key) => {
            payload[key] = value;
        });
        return payload;
    }

    function queueForm(form) {
        const action = form.action;
        const label = form.dataset.offlineLabel || 'form';
        const replaceKey = label === 'collection' ? action : null;
        const queue = replaceKey
            ? readQueue().filter(item => item.replaceKey !== replaceKey)
            : readQueue();

        queue.push({
            action,
            method: form.method || 'POST',
            payload: formToPayload(form),
            label,
            replaceKey,
            queuedAt: new Date().toISOString(),
        });

        writeQueue(queue);
        showBanner(`Saved ${label} offline. It will sync when connection returns.`, 'warning');
        clearTextInputs(form);
    }

    function clearTextInputs(form) {
        form.querySelectorAll('input[type="text"]').forEach(input => {
            input.value = '';
        });
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

        showBanner(`Syncing ${queue.length} offline item${queue.length === 1 ? '' : 's'}...`, 'info');

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
            showBanner(`${remaining.length} offline item${remaining.length === 1 ? '' : 's'} still pending. Keep this device logged in and try again online.`, 'warning');
            return;
        }

        showBanner('Offline items synced.', 'success');
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
        let banner = document.getElementById('offlineSyncBanner');
        if (banner) return banner;

        banner = document.createElement('div');
        banner.id = 'offlineSyncBanner';
        banner.style.cssText = [
            'position:fixed',
            'left:16px',
            'right:16px',
            'top:calc(16px + env(safe-area-inset-top))',
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
            showBanner(pending ? `${pending} offline item${pending === 1 ? '' : 's'} waiting to sync.` : 'Offline mode. Supported forms will be saved on this device.', 'warning');
            return;
        }

        if (pending) {
            showBanner(`${pending} offline item${pending === 1 ? '' : 's'} pending sync.`, 'info');
            return;
        }

        banner.style.display = 'none';
    }

    offlineForms.forEach(form => {
        form.addEventListener('submit', event => {
            if (navigator.onLine) return;

            event.preventDefault();
            queueForm(form);
        });
    });

    window.addEventListener('online', syncQueue);
    window.addEventListener('offline', updateBanner);
    window.addEventListener('load', () => {
        updateBanner();
        syncQueue();
        prefetchCollectionPages();
    });
})();

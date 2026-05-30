import './bootstrap';
import './filter';

let deferredInstallPrompt = null;
// const installButton = document.getElementById('install-app-btn');

window.addEventListener('beforeinstallprompt', (event) => {
    event.preventDefault();
    deferredInstallPrompt = event;
    installButton?.classList.remove('hidden');
});

// installButton?.addEventListener('click', async () => {
//     if (!deferredInstallPrompt) {
//         return;
//     }

//     deferredInstallPrompt.prompt();
//     await deferredInstallPrompt.userChoice;
//     deferredInstallPrompt = null;
//     installButton.classList.add('hidden');
// });

window.addEventListener('appinstalled', () => {
    deferredInstallPrompt = null;
    installButton?.classList.add('hidden');
});

if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js').catch(() => {

        });
    });
}

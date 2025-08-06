self.addEventListener('push', (e) => {
    const data = e.data.json();
    self.registration.showNotification(data.title, {
        body: data.body,
        icon: '/icon.png', // Chemin relatif depuis public/
        vibrate: [200, 100, 200] // Optionnel : vibration sur mobile
    });
});

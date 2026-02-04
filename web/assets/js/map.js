

document.addEventListener('DOMContentLoaded', () => {
    const map = L.map('map').setView([51.0, 10.5], 7);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(map);

    L.tileLayer.wms(
        'https://uas-betrieb.de/geoservices/dipul/wms',
        {
            layers: 'dipul', // <-- имя слоя из GetCapabilities
            format: 'image/png',
            transparent: true,
            version: '1.3.0'
        }
    ).addTo(map);
});

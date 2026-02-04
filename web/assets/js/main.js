/**
* Template Name: Logis
* Template URL: https://bootstrapmade.com/logis-bootstrap-logistics-website-template/
* Updated: Aug 07 2024 with Bootstrap v5.3.3
* Author: BootstrapMade.com
* License: https://bootstrapmade.com/license/
*/
(function() {
  "use strict";

  /**
   * Apply .scrolled class to the body as the page is scrolled down
   */
  function toggleScrolled() {
    const selectBody = document.querySelector('body');
    const selectHeader = document.querySelector('#header');
    if (!selectHeader.classList.contains('scroll-up-sticky') && !selectHeader.classList.contains('sticky-top') && !selectHeader.classList.contains('fixed-top')) return;
    window.scrollY > 100 ? selectBody.classList.add('scrolled') : selectBody.classList.remove('scrolled');
  }

  document.addEventListener('scroll', toggleScrolled);
  window.addEventListener('load', toggleScrolled);

  /**
   * Mobile nav toggle
   */
  const mobileNavToggleBtn = document.querySelector('.mobile-nav-toggle');

  function mobileNavToogle() {
    document.querySelector('body').classList.toggle('mobile-nav-active');
    mobileNavToggleBtn.classList.toggle('bi-list');
    mobileNavToggleBtn.classList.toggle('bi-x');
  }
  mobileNavToggleBtn.addEventListener('click', mobileNavToogle);

  /**
   * Hide mobile nav on same-page/hash links
   */
  document.querySelectorAll('#navmenu a').forEach(navmenu => {
    navmenu.addEventListener('click', () => {
      if (document.querySelector('.mobile-nav-active')) {
        mobileNavToogle();
      }
    });

  });

  /**
   * Toggle mobile nav dropdowns
   */
  document.querySelectorAll('.navmenu .toggle-dropdown').forEach(navmenu => {
    navmenu.addEventListener('click', function(e) {
      e.preventDefault();
      this.parentNode.classList.toggle('active');
      this.parentNode.nextElementSibling.classList.toggle('dropdown-active');
      e.stopImmediatePropagation();
    });
  });

  /**
   * Preloader
   */
  const preloader = document.querySelector('#preloader');
  if (preloader) {
    window.addEventListener('load', () => {
      preloader.remove();
    });
  }

  /**
   * Scroll top button
   */
  let scrollTop = document.querySelector('.scroll-top');

  function toggleScrollTop() {
    if (scrollTop) {
      window.scrollY > 100 ? scrollTop.classList.add('active') : scrollTop.classList.remove('active');
    }
  }
  scrollTop.addEventListener('click', (e) => {
    e.preventDefault();
    window.scrollTo({
      top: 0,
      behavior: 'smooth'
    });
  });

  window.addEventListener('load', toggleScrollTop);
  document.addEventListener('scroll', toggleScrollTop);

  /**
   * Animation on scroll function and init
   */
  function aosInit() {
    AOS.init({
      duration: 600,
      easing: 'ease-in-out',
      once: true,
      mirror: false
    });
  }
  window.addEventListener('load', aosInit);

  /**
   * Initiate Pure Counter
   */
  new PureCounter();

  /**
   * Initiate glightbox
   */
  // const glightbox = GLightbox({
  //   selector: '.glightbox'
  // });

  /**
   * Init swiper sliders
   */
  function initSwiper() {
    document.querySelectorAll(".init-swiper").forEach(function(swiperElement) {
      let config = JSON.parse(
        swiperElement.querySelector(".swiper-config").innerHTML.trim()
      );

      if (swiperElement.classList.contains("swiper-tab")) {
        initSwiperWithCustomPagination(swiperElement, config);
      } else {
        new Swiper(swiperElement, config);
      }
    });
  }

  window.addEventListener("load", initSwiper);

  /**
   * Frequently Asked Questions Toggle
   */
  document.querySelectorAll('.faq-item h3, .faq-item .faq-toggle').forEach((faqItem) => {
    faqItem.addEventListener('click', () => {
      faqItem.parentNode.classList.toggle('faq-active');
    });
  });

})();

const videoElements = document.querySelectorAll('[data-video-wrap]');

videoElements?.forEach((videoElement) => {
    const btnElement = videoElement.querySelector('.pulsating-play-btn');
    const videoId = btnElement.dataset.id;

    videoElement.addEventListener('click', (e) => {
        e.preventDefault();

        new YT.Player(videoElement, {
            height: '477',
            width: '636',
            videoId,
            playerVars: {
                autoplay: 1,
                modestbranding: 1,
                rel: 0,
            },
            events: {
                onReady: () => {
                    btnElement.remove();
                }
            }
        });
    });
});

const mapEl = document.querySelector('#map');

if (mapEl) {
    const droneId = mapEl.dataset.drone;
    const datetime = mapEl.dataset.datetime;

    const ZONES_MIN_ZOOM = 10;
    const DEFAULT_CENTER = [50.9, 11.0];
    const DEFAULT_ZOOM = 8;

    const USER_ZOOM = 12;
    const WMS_BASE_URL = 'https://uas-betrieb.de/geoservices/dipul/ows';

    const map = L.map('map', {
        zoomControl: true,
        inertia: true
    });

    L.tileLayer(
        'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
        {
            attribution: '© OpenStreetMap',
            maxZoom: 20
        }
    ).addTo(map);

    const zonesConfig = [
        {
            layers: [
                'bahnanlagen',
                'bundesautobahnen',
                'bundesstrassen',
                'binnenwasserstrassen',
                'seewasserstrassen',
                'schifffahrtsanlagen',
                'stromleitungen'
            ]
        },
        {
            layers: [
                'flughaefen',
                'flugplaetze',
                'kontrollzonen',
                'flugbeschraenkungsgebiete',
                'temporaere_betriebseinschraenkungen',
                'inaktive_temporaere_betriebseinschraenkungen',
                'modellflugplaetze',
                'haengegleiter'
            ]
        },
        {
            layers: [
                'behoerden',
                'polizei',
                'sicherheitsbehoerden',
                'internationale_organisationen',
                'diplomatische_vertretungen',
                'justizvollzugsanstalten',
                'militaerische_anlagen'
            ]
        },
        {
            layers: [
                'industrieanlagen',
                'kraftwerke',
                'umspannwerke',
                'windkraftanlagen'
            ]
        },
        {
            layers: [
                'nationalparks',
                'naturschutzgebiete',
                'ffh-gebiete',
                'vogelschutzgebiete'
            ]
        },
        {
            layers: [
                'wohngrundstuecke',
                'freibaeder'
            ]
        }
    ];

    const allWmsLayers = zonesConfig
        .flatMap(group => group.layers)
        .join(',');

    const zonesLayer = L.tileLayer.wms(WMS_BASE_URL, {
        layers: allWmsLayers,
        format: 'image/png',
        transparent: true,
        version: '1.3.0',

        minZoom: ZONES_MIN_ZOOM,
        maxZoom: 20,

        updateWhenIdle: false,
        updateWhenZooming: true,

        keepBuffer: 2,
        tileSize: 256
    });

    zonesLayer.addTo(map);

    function initWithGeolocation() {
        if (!navigator.geolocation) {
            map.setView(DEFAULT_CENTER, DEFAULT_ZOOM);
            return;
        }

        navigator.geolocation.getCurrentPosition(
            pos => {
                const latlng = [
                    pos.coords.latitude,
                    pos.coords.longitude
                ];

                map.setView(latlng, USER_ZOOM);
            },
            err => {
                console.warn('Geolocation failed:', err.message);
                map.setView(DEFAULT_CENTER, DEFAULT_ZOOM);
            },
            {
                enableHighAccuracy: true,
                timeout: 8000,
                maximumAge: 60000
            }
        );
    }

    initWithGeolocation();

    let clickMarker = null;

    map.on('click', async (e) => {
        if (clickMarker) {
            map.removeLayer(clickMarker);
        }

        clickMarker = L.marker(e.latlng).addTo(map);

        const lat = e.latlng.lat.toFixed(6);
        const lng = e.latlng.lng.toFixed(6);

        const point = map.latLngToContainerPoint(e.latlng);
        const size = map.getSize();
        const bounds = map.getBounds();

        const wmsParams = new URLSearchParams({
            SERVICE: 'WMS',
            VERSION: '1.3.0',
            REQUEST: 'GetFeatureInfo',
            LAYERS: allWmsLayers,
            QUERY_LAYERS: allWmsLayers,
            CRS: 'EPSG:4326',
            BBOX: `${bounds.getSouth()},${bounds.getWest()},${bounds.getNorth()},${bounds.getEast()}`,
            WIDTH: size.x,
            HEIGHT: size.y,
            I: Math.round(point.x),
            J: Math.round(point.y),
            INFO_FORMAT: 'application/json'
        });

        let zones = [];

        try {
            const res = await fetch(`${WMS_BASE_URL}?${wmsParams.toString()}`);
            const data = await res.json();

            if (data.features?.length) {
                zones = data.features.map(f => ({
                    id: f.id,
                    name:
                        f.properties?.name ||
                        f.properties?.bezeichnung ||
                        f.id
                }));
            }
        } catch (err) {
            console.warn('GetFeatureInfo error', err);
        }

        if (droneId && datetime) {
             const params = new URLSearchParams({
                drone: droneId,
                datetime: datetime,
                lat: lat,
                lng: lng,
                zones: JSON.stringify(zones)
            });

            const response = await fetch(
                `/actions/site/map/calc?${params.toString()}`
            );

            const html = await response.text();
            document.querySelector('#map-info').innerHTML = html;
        }
    });
}



const searchEl = document.getElementById('drone-search');

if (searchEl) {
    const results = document.getElementById('drone-results');
    const hiddenDroneId = document.getElementById('drone-id');
    const dateTimeInput = document.querySelector('input[type="datetime-local"]');
    const mapSlug = document.querySelector('[data-map-slug]')?.dataset.mapSlug;

    let abortController = null;
    let debounceTimer = null;
    let lastQuery = '';

    function clearResults() {
        results.innerHTML = '';
        results.classList.remove('show');
    }

    function renderResults(data) {
        results.innerHTML = '';

        if (!data.length) {
            return;
        }

        data.forEach(drone => {
            const item = document.createElement('button');
            item.type = 'button';
            item.className = 'dropdown-item';
            item.textContent = drone.title;

            item.addEventListener('click', () => {
                searchEl.value = drone.title;
                hiddenDroneId.value = drone.id;
                clearResults();
            });

            results.appendChild(item);
        });

        results.classList.add('show');
    }

    async function searchDrones(query) {
        if (abortController) {
            abortController.abort();
        }

        abortController = new AbortController();

        const response = await fetch(
            `/actions/site/drones/search?q=${encodeURIComponent(query)}`,
            { signal: abortController.signal }
        );

        if (!response.ok) return;

        const data = await response.json();

        if (query !== lastQuery) {
            return;
        }

        renderResults(data);
    }

    searchEl.addEventListener('input', () => {
        const value = searchEl.value.trim();
        hiddenDroneId.value = '';
        lastQuery = value;

        if (value.length < 2) {
            clearResults();
            return;
        }

        clearTimeout(debounceTimer);

        debounceTimer = setTimeout(() => {
            searchDrones(value);
        }, 250);
    });

    document.addEventListener('click', (e) => {
        if (!e.target.closest('.form-search')) {
            clearResults();
        }
    });

    document.querySelector('.form-search').addEventListener('submit', (e) => {
        e.preventDefault();

        const droneId = hiddenDroneId.value;
        const datetime = dateTimeInput.value;

        if (!droneId) {
            searchEl.classList.add('is-invalid');
            return;
        } else {
            searchEl.classList.remove('is-invalid');
        }

        const params = new URLSearchParams({
            drone: droneId,
            datetime: datetime
        });

        if (mapSlug) {
            window.location.href = `/${mapSlug}?${params.toString()}`;
        }
    });

}

const datetimeInput = document.querySelector('input[type="datetime-local"]');

if (datetimeInput && !datetimeInput.value) {
    const now = new Date();

    const pad = n => String(n).padStart(2, '0');

    const value =
        now.getFullYear() + '-' +
        pad(now.getMonth() + 1) + '-' +
        pad(now.getDate()) + 'T' +
        pad(now.getHours()) + ':' +
        pad(now.getMinutes());

    datetimeInput.value = value;
}
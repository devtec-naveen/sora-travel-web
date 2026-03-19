/**
 * app.js — Global Application Script
 * Sections:
 *  1.  Loader
 *  2.  Livewire Modal Helpers
 *  3.  View Toggle (Grid / List)
 *  4.  Tabler Icon Loader
 *  5.  Tab System (Main + Sub Tabs)
 *  6.  Filter Sidebar (Mobile)
 *  7.  Swiper — Destinations Slider
 *  8.  Traveler / PAX Dropdown
 *  9.  Airport & Hotel Search (AP Field)
 * 10.  Date-Time Picker (DTP)
 * 11.  Trip-Type Tab Toggle
 * 12.  Hotel Guests (HG) Dropdown
 * 13.  Multi-City Flight Rows
 * 14.  Flight Form Validator
 * 15.  Hero Search Box Toggle
 */

/* ═══════════════════════════════════════════════════════════════
   1. LOADER
═══════════════════════════════════════════════════════════════ */

/**
 * Show the full-page loader on DOM ready, then hide after 1 s.
 */
function initLoader() {
    const loader = document.getElementById('Loader');
    if (loader) {
        loader.style.display = 'flex';
        setTimeout(() => { loader.style.display = 'none'; }, 1000);
    }
}

/* ═══════════════════════════════════════════════════════════════
   2. LIVEWIRE MODAL HELPERS
═══════════════════════════════════════════════════════════════ */

/**
 * Register Livewire event listeners for opening / closing <dialog> modals.
 * Called once after Livewire is initialised.
 */
function initLivewireModals() {
    Livewire.on('open-modal',  (data) => { document.getElementById(data.id)?.showModal(); });
    Livewire.on('close-modal', (data) => { document.getElementById(data.id)?.close(); });
}

/* ═══════════════════════════════════════════════════════════════
   3. VIEW TOGGLE  (Grid / List)
═══════════════════════════════════════════════════════════════ */

/**
 * Wire up list-view / grid-view toggle buttons.
 * Defaults to grid on init.
 */
function initViewToggle() {
    const listBtn = document.getElementById('list-view-btn');
    const gridBtn = document.getElementById('grid-view-btn');
    const wrapper = document.getElementById('results-wrapper');

    if (!listBtn || !gridBtn || !wrapper) {
        console.warn('View toggle elements not found');
        return;
    }

    /**
     * Switch the results wrapper between list and grid layout.
     * @param {'list'|'grid'} view
     */
    function setView(view) {
        if (view === 'list') {
            listBtn.classList.add('active');
            gridBtn.classList.remove('active');
            wrapper.classList.add('list-view');
            wrapper.classList.remove('grid-cols-2', 'sm:grid-cols-2', 'lg:grid-cols-3');
            wrapper.classList.add('grid-cols-1');
        } else {
            gridBtn.classList.add('active');
            listBtn.classList.remove('active');
            wrapper.classList.remove('list-view', 'grid-cols-1');
            wrapper.classList.add('grid-cols-2', 'sm:grid-cols-2', 'lg:grid-cols-3');
        }
    }

    listBtn.addEventListener('click', () => setView('list'));
    gridBtn.addEventListener('click', () => setView('grid'));
    setView('grid');
}

/* ═══════════════════════════════════════════════════════════════
   4. TABLER ICON LOADER
═══════════════════════════════════════════════════════════════ */

/**
 * Fetch SVG icons from the Tabler CDN and inject them inline.
 * Reads `data-tabler`, `data-size`, and `data-stroke` attributes.
 */
function initTablerIcons() {
    document.querySelectorAll('i[data-tabler]').forEach(async (icon) => {
        const iconName = icon.getAttribute('data-tabler').toLowerCase();
        const url = `https://cdn.jsdelivr.net/npm/@tabler/icons@latest/icons/${iconName}.svg`;
        try {
            const response = await fetch(url);
            if (!response.ok) { console.warn(`Icon "${iconName}" failed to load`); return; }
            const svgText = await response.text();
            icon.innerHTML = svgText;
            const svg = icon.querySelector('svg');
            if (svg) {
                svg.setAttribute('width',        icon.getAttribute('data-size')   || '24');
                svg.setAttribute('height',       icon.getAttribute('data-size')   || '24');
                svg.setAttribute('stroke-width', icon.getAttribute('data-stroke') || '1.5');
                svg.style.cssText = 'display:inline-block;vertical-align:middle;stroke:currentColor;';
            }
        } catch (err) {
            console.error('Icon fetching error:', err);
        }
    });
}

/* ═══════════════════════════════════════════════════════════════
   5. TAB SYSTEM  (Main + Sub Tabs)
═══════════════════════════════════════════════════════════════ */

/**
 * Initialise main `.tabs-border` tabs and nested `.tabs` sub-tabs.
 */
function initTabs() {
    const mainTabs   = document.querySelectorAll('.tabs-border');
    const mainPanels = document.querySelectorAll('.search-tab-content > div');

    if (mainTabs.length && mainPanels.length) {
        mainTabs.forEach((tab, index) => {
            tab.addEventListener('click', () => {
                mainTabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');
                mainPanels.forEach((panel, pIndex) => {
                    panel.classList.toggle('hidden', index !== pIndex);
                });
            });
        });
    }

    document.querySelectorAll('.search-tab-content > div').forEach((panel) => {
        const subTabs   = panel.querySelectorAll('.tabs');
        const subPanels = panel.querySelectorAll('.subTabs-content > div');
        if (subTabs.length && subPanels.length) {
            subTabs.forEach((tab, index) => {
                tab.addEventListener('click', () => {
                    subTabs.forEach(t => t.classList.remove('active'));
                    tab.classList.add('active');
                    subPanels.forEach((subPanel, pIndex) => {
                        subPanel.classList.toggle('hidden', index !== pIndex);
                    });
                });
            });
        }
    });
}

/* ═══════════════════════════════════════════════════════════════
   6. FILTER SIDEBAR  (Mobile)
═══════════════════════════════════════════════════════════════ */

/**
 * Wire up the mobile filter sidebar open / close / backdrop behaviour.
 */
function initFilterSidebar() {
    const openFilterBtn  = document.getElementById('open-filter');
    const closeFilterBtn = document.getElementById('close-filter');
    const applyFilterBtn = document.getElementById('apply-filter');
    const filterSidebar  = document.getElementById('filter-sidebar');
    const filterBackdrop = document.getElementById('filter-backdrop');

    if (!openFilterBtn || !filterSidebar || !filterBackdrop) return;

    /**
     * Open or close the filter sidebar.
     * @param {boolean} isOpen
     */
    function toggleFilter(isOpen) {
        if (isOpen) {
            filterSidebar.classList.remove('translate-x-[-100%]');
            filterBackdrop.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        } else {
            filterSidebar.classList.add('translate-x-[-100%]');
            filterBackdrop.classList.add('hidden');
            document.body.style.overflow = '';
        }
    }

    openFilterBtn.addEventListener('click',  () => toggleFilter(true));
    closeFilterBtn?.addEventListener('click', () => toggleFilter(false));
    applyFilterBtn?.addEventListener('click', () => toggleFilter(false));
    filterBackdrop.addEventListener('click',  () => toggleFilter(false));
}

/* ═══════════════════════════════════════════════════════════════
   7. SWIPER — DESTINATIONS SLIDER
═══════════════════════════════════════════════════════════════ */

/**
 * Initialise the Destinations Swiper carousel.
 * Requires the `swiper` library to be loaded.
 */
function initDestinationsSwiper() {
    if (typeof Swiper === 'undefined') return;
    new Swiper('.DestinationsSlider', {
        loop: true,
        navigation: { nextEl: '.swiper-button-next1', prevEl: '.swiper-button-prev1' },
        pagination: { el: '.swiper-pagination', clickable: true },
        breakpoints: {
            0:    { slidesPerView: 2 },
            640:  { slidesPerView: 4 },
            1100: { slidesPerView: 5 },
            1300: { slidesPerView: 8 },
        },
    });
}

/* ═══════════════════════════════════════════════════════════════
   8. TRAVELER / PAX DROPDOWN
═══════════════════════════════════════════════════════════════ */

/** In-memory PAX state keyed by widget id. @type {Object.<string, {adults:number, children:number, infants:number}>} */
const PAX = {};

/**
 * Initialise a PAX widget with default counts.
 * @param {string} id
 * @param {number} [adults=1]
 * @param {number} [children=0]
 * @param {number} [infants=0]
 */
function initPax(id, adults = 1, children = 0, infants = 0) {
    PAX[id] = { adults: Number(adults), children: Number(children), infants: Number(infants) };
    const el = document.getElementById(`${id}_adults-count`);
    if (el) { el.innerHTML = ''; el.textContent = PAX[id].adults; }
    updateTravelersLabel(id);
}

/**
 * Toggle the PAX dropdown open / closed.
 * @param {string} id
 */
function toggleTravelers(id) {
    document.getElementById(id + 'Dropdown')?.classList.toggle('hidden');
}

/**
 * Close a PAX dropdown.
 * @param {string} id
 */
function closeTravelers(id) {
    document.getElementById(id + 'Dropdown')?.classList.add('hidden');
}

/**
 * Ensure a PAX entry exists for the given id.
 * @param {string} id
 */
function ensurePax(id) {
    if (!PAX[id]) PAX[id] = { adults: 1, children: 0, infants: 0 };
}

/**
 * Increment or decrement a PAX count within its allowed range.
 * @param {string} id
 * @param {'adults'|'children'|'infants'} type
 * @param {1|-1} delta
 */
function changePax(id, type, delta) {
    ensurePax(id);
    const limits = { adults: [1, 9], children: [0, 9], infants: [0, 9] };
    const [min, max] = limits[type];
    PAX[id][type] = Math.min(max, Math.max(min, PAX[id][type] + delta));
    document.getElementById(`${id}_${type}-count`).textContent = PAX[id][type];
    updateTravelersLabel(id);
}

/**
 * Refresh the traveler summary label and sync hidden inputs.
 * @param {string} id
 */
function updateTravelersLabel(id) {
    ensurePax(id);
    const wrapper = document.getElementById(id + 'Wrapper');
    const cls = wrapper?.querySelector(`input[name='${id}_cabinClass']:checked`)?.value || 'Economy';
    const total = PAX[id].adults + PAX[id].children + PAX[id].infants;
    const word  = total === 1 ? 'Traveler' : 'Travelers';

    document.getElementById(id + 'Label').textContent              = `${total} ${word}, ${cls}`;
    document.getElementById(id + '_inp_adults').value              = PAX[id].adults;
    document.getElementById(id + '_inp_children').value            = PAX[id].children;
    document.getElementById(id + '_inp_infants').value             = PAX[id].infants;
    document.getElementById(id + '_inp_class').value               = cls;
}

/* ═══════════════════════════════════════════════════════════════
   9. AIRPORT & HOTEL SEARCH  (AP Field)
═══════════════════════════════════════════════════════════════ */

/** Static airport list */
const AIRPORTS = [
    { code:'JAI', city:'Jaipur',     name:'Jaipur International Airport',               country:'India'     },
    { code:'DEL', city:'Delhi',      name:'Indira Gandhi International Airport',         country:'India'     },
    { code:'BLR', city:'Bangalore',  name:'Kempegowda International Airport',            country:'India'     },
    { code:'BOM', city:'Mumbai',     name:'Chhatrapati Shivaji Maharaj Intl Airport',    country:'India'     },
    { code:'CCU', city:'Kolkata',    name:'Netaji Subhash Chandra Bose Airport',         country:'India'     },
    { code:'HYD', city:'Hyderabad',  name:'Rajiv Gandhi International Airport',          country:'India'     },
    { code:'MAA', city:'Chennai',    name:'Chennai International Airport',               country:'India'     },
    { code:'AMD', city:'Ahmedabad',  name:'Sardar Vallabhbhai Patel Intl Airport',       country:'India'     },
    { code:'COK', city:'Kochi',      name:'Cochin International Airport',                country:'India'     },
    { code:'IXC', city:'Chandigarh', name:'Chandigarh International Airport',            country:'India'     },
    { code:'DXB', city:'Dubai',      name:'Dubai International Airport',                 country:'UAE'       },
    { code:'AUH', city:'Abu Dhabi',  name:'Zayed International Airport',                 country:'UAE'       },
    { code:'LHR', city:'London',     name:'Heathrow Airport',                            country:'UK'        },
    { code:'SIN', city:'Singapore',  name:'Changi Airport',                              country:'Singapore' },
    { code:'BKK', city:'Bangkok',    name:'Suvarnabhumi Airport',                        country:'Thailand'  },
    { code:'CDG', city:'Paris',      name:'Charles de Gaulle Airport',                   country:'France'    },
    { code:'JFK', city:'New York',   name:'John F. Kennedy International Airport',       country:'USA'       },
    { code:'NRT', city:'Tokyo',      name:'Narita International Airport',                country:'Japan'     },
];

/** Static hotel city list */
const HOTELS = [
    { code:'JAI', city:'Jaipur',     name:'Hotels in Jaipur',     country:'India',     latitude:26.9124,  longitude:75.7873  },
    { code:'DEL', city:'Delhi',      name:'Hotels in Delhi',      country:'India',     latitude:28.6139,  longitude:77.2090  },
    { code:'BLR', city:'Bangalore',  name:'Hotels in Bangalore',  country:'India',     latitude:12.9716,  longitude:77.5946  },
    { code:'BOM', city:'Mumbai',     name:'Hotels in Mumbai',     country:'India',     latitude:19.0760,  longitude:72.8777  },
    { code:'CCU', city:'Kolkata',    name:'Hotels in Kolkata',    country:'India',     latitude:22.5726,  longitude:88.3639  },
    { code:'HYD', city:'Hyderabad',  name:'Hotels in Hyderabad',  country:'India',     latitude:17.3850,  longitude:78.4867  },
    { code:'MAA', city:'Chennai',    name:'Hotels in Chennai',    country:'India',     latitude:13.0827,  longitude:80.2707  },
    { code:'AMD', city:'Ahmedabad',  name:'Hotels in Ahmedabad',  country:'India',     latitude:23.0225,  longitude:72.5714  },
    { code:'GOA', city:'Goa',        name:'Hotels in Goa',        country:'India',     latitude:15.2993,  longitude:74.1240  },
    { code:'DXB', city:'Dubai',      name:'Hotels in Dubai',      country:'UAE',       latitude:25.2048,  longitude:55.2708  },
    { code:'BKK', city:'Bangkok',    name:'Hotels in Bangkok',    country:'Thailand',  latitude:13.7563,  longitude:100.5018 },
    { code:'SIN', city:'Singapore',  name:'Hotels in Singapore',  country:'Singapore', latitude:1.3521,   longitude:103.8198 },
    { code:'LHR', city:'London',     name:'Hotels in London',     country:'UK',        latitude:51.5074,  longitude:-0.1278  },
    { code:'CDG', city:'Paris',      name:'Hotels in Paris',      country:'France',    latitude:48.8566,  longitude:2.3522   },
    { code:'JFK', city:'New York',   name:'Hotels in New York',   country:'USA',       latitude:40.7128,  longitude:-74.0060 },
];

const TOP        = ['DEL','BLR','BOM','CCU','JAI','HYD','MAA','DXB'];
const TOP_HOTELS = ['DEL','BLR','BOM','JAI','GOA','HYD','MAA','DXB'];

const AP_API_URLS = { airport: 'airport-search', hotel: 'hotels/suggestions' };

/** Debounce timers keyed by field id. @type {Object.<string, number>} */
const _apTimers = {};

const AP_ICONS = {
    plane: `<svg viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>`,
    hotel: `<svg viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>`,
};

/**
 * Fetch airport / hotel suggestions from the API.
 * Returns `null` on failure so the caller can fall back to local data.
 * @param {string} q
 * @param {'airport'|'hotel'} type
 * @returns {Promise<Array|null>}
 */
async function apApiSearch(q, type) {
    const url = AP_API_URLS[type] || AP_API_URLS.airport;
    try {
        const r = await fetch(`${url}?keyword=${encodeURIComponent(q)}`, {
            headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        });
        if (!r.ok) throw 0;
        const j    = await r.json();
        const list = j.data ?? [];
        return list.map(a => ({
            code     : a.code        ?? a.iata_city_code ?? a.iata_code ?? a.city_code ?? '',
            city     : a.city        ?? a.city_name      ?? a.name      ?? '',
            name     : a.name        ?? '',
            country  : a.country     ?? a.iata_country_code ?? '',
            latitude : a.latitude    != null ? String(a.latitude)  : '',
            longitude: a.longitude   != null ? String(a.longitude) : '',
        }));
    } catch {
        return null;
    }
}

/**
 * Filter the local static list by a keyword.
 * Returns the full list when no query is supplied.
 * @param {string} q
 * @param {'airport'|'hotel'} type
 * @returns {Array}
 */
function apLocalSearch(q, type) {
    const list = type === 'hotel' ? HOTELS : AIRPORTS;
    if (!q) return list;
    const lq = q.toLowerCase();
    return list.filter(a =>
        a.city.toLowerCase().includes(lq) ||
        a.code.toLowerCase().includes(lq) ||
        a.name.toLowerCase().includes(lq)
    );
}

/**
 * Get recent searches from localStorage for a given field.
 * @param {string} fieldId
 * @param {'airport'|'hotel'} type
 * @returns {Array}
 */
function apGetRecents(fieldId, type) {
    return JSON.parse(localStorage.getItem(`recent_${type || 'airport'}_${fieldId}`)) || [];
}

/**
 * Persist a selected item to the recent-searches list (max 5).
 * @param {string} fieldId
 * @param {Object} item
 * @param {'airport'|'hotel'} type
 */
function apSaveRecent(fieldId, item, type) {
    const key  = `recent_${type || 'airport'}_${fieldId}`;
    let   list = apGetRecents(fieldId, type);
    list = list.filter(a => a.code !== item.code);
    list.unshift(item);
    list = list.slice(0, 5);
    localStorage.setItem(key, JSON.stringify(list));
}

/**
 * Debounce a function call, namespaced by key.
 * @param {Function} fn
 * @param {string} key
 * @param {number} [ms=300]
 */
function apDebounce(fn, key, ms = 300) {
    clearTimeout(_apTimers[key]);
    _apTimers[key] = setTimeout(fn, ms);
}

/**
 * Return the inline SVG icon for the given type.
 * @param {'airport'|'hotel'} type
 * @returns {string}
 */
function apGetIcon(type) {
    return type === 'hotel' ? AP_ICONS.hotel : AP_ICONS.plane;
}

/**
 * Build the HTML for a single airport / hotel option row.
 * @param {Object} a
 * @param {'airport'|'hotel'} type
 * @returns {string}
 */
function apOptionHTML(a, type) {
    const cityName = a.city && typeof a.city === 'object' ? a.city.name : a.city;
    return `
    <div class="ap-option" data-code="${a.code}" data-city="${cityName}" data-name="${a.name}"
         data-country="${a.country}" data-latitude="${a.latitude || ''}" data-longitude="${a.longitude || ''}">
      <div class="ap-opt-icon">${apGetIcon(type)}</div>
      <div class="ap-opt-body">
        <div class="ap-opt-title">${cityName} <span class="ap-opt-code">(${a.code})</span></div>
        <div class="ap-opt-sub">${a.name}</div>
      </div>
      <div class="ap-opt-cntry">${a.country}</div>
    </div>`;
}

/**
 * Build a labelled group of options.
 * @param {string} label
 * @param {Array}  list
 * @param {'airport'|'hotel'} type
 * @returns {string}
 */
function apGroupHTML(label, list, type) {
    return `<div class="ap-group-label">${label}</div>` + list.map(a => apOptionHTML(a, type)).join('');
}

/**
 * Render the results panel with grouping / empty state.
 * @param {Element} resultsEl
 * @param {Array}   data
 * @param {string}  query
 * @param {string}  fieldId
 * @param {'airport'|'hotel'} type
 */
function apRenderResults(resultsEl, data, query, fieldId, type) {
    const topList = type === 'hotel' ? TOP_HOTELS : TOP;
    if (!query) {
        const recent = apGetRecents(fieldId, type);
        let html = '';
        if (recent.length)  html += apGroupHTML('Recent Searches', recent, type);
        const top  = data.filter(a =>  topList.includes(a.code));
        const rest = data.filter(a => !topList.includes(a.code));
        if (top.length)  html += apGroupHTML(type === 'hotel' ? 'Popular Cities' : 'Top Cities', top, type);
        if (rest.length) html += apGroupHTML('Other Results', rest, type);
        resultsEl.innerHTML = html;
        return;
    }
    if (!data.length) { resultsEl.innerHTML = `<div class="ap-empty">No results found</div>`; return; }
    resultsEl.innerHTML = data.map(a => apOptionHTML(a, type)).join('');
}

/**
 * Initialise a single `.ap-field` element with its dropdown, search and selection logic.
 * @param {Element} fieldEl
 */
function apInitField(fieldEl) {
    const id         = fieldEl.dataset.id;
    const type       = fieldEl.dataset.type || 'airport';
    const dropdown   = fieldEl.querySelector('.ap-dropdown');
    const searchInp  = fieldEl.querySelector('.ap-search-input');
    const resultsEl  = fieldEl.querySelector('.ap-results');
    const display    = fieldEl.querySelector('.ap-display');
    const hidden     = fieldEl.querySelector('.ap-hidden');
    const cityHidden = fieldEl.querySelector('.ap-city-hidden');
    const localData  = type === 'hotel' ? HOTELS : AIRPORTS;

    // purana listener cleanly remove karo
    if (fieldEl._apAbort) fieldEl._apAbort.abort();
    fieldEl._apAbort = new AbortController();
    const signal = fieldEl._apAbort.signal;

    function openDropdown() {
        document.querySelectorAll('.ap-dropdown.open').forEach(d => { if (d !== dropdown) d.classList.remove('open'); });
        dropdown.classList.add('open');
        searchInp.value = '';
        searchInp.focus();
        apRenderResults(resultsEl, localData, '', id, type);
        bindOptionClicks();
    }

    function closeDropdown() {
        dropdown.classList.remove('open');
        searchInp.value = '';
    }

    async function loadResults(q) {
        resultsEl.innerHTML = `<div class="ap-empty" style="padding:16px"><span class="loading loading-dots loading-lg"></span></div>`;
        let data = await apApiSearch(q, type);
        if (!data) data = apLocalSearch(q, type);
        apRenderResults(resultsEl, data, q, id, type);
        bindOptionClicks();
    }

    function bindOptionClicks() {
        resultsEl.querySelectorAll('.ap-option').forEach(opt => {
            opt.addEventListener('click', () => {
                const { code, city, name, country } = opt.dataset;
                const latitude  = opt.dataset.latitude  || '';
                const longitude = opt.dataset.longitude || '';

                apSaveRecent(id, { code, city, name, country, latitude, longitude }, type);
                display.textContent = type === 'hotel' ? city : `${code} – ${city}`;
                display.classList.remove('text-slate-400');
                display.classList.add('text-slate-800');
                hidden.value = type === 'hotel' ? city : code;

                if (type === 'hotel') {
                    const latEl = fieldEl.querySelector('.ap-lat-hidden');
                    const lngEl = fieldEl.querySelector('.ap-lng-hidden');
                    if (latEl) latEl.value = latitude;
                    if (lngEl) lngEl.value = longitude;
                }
                if (cityHidden) cityHidden.value = city;
                closeDropdown();
            });
        });
    }

    fieldEl.addEventListener('click', e => { if (!dropdown.contains(e.target)) openDropdown(); }, { signal });
    searchInp.addEventListener('input', () => { apDebounce(() => loadResults(searchInp.value.trim()), id); }, { signal });
    dropdown.addEventListener('click', e => e.stopPropagation(), { signal });

    // Apply default selection if data-default is present
    const defCode = fieldEl.dataset.default;
    if (defCode) {
        const ap = localData.find(a => a.code === defCode);
        if (ap) {
            display.textContent = type === 'hotel' ? ap.city : `${ap.code} – ${ap.city}`;
            hidden.value        = type === 'hotel' ? ap.city : ap.code;
        }
    }
}

/**
 * Initialise every `.ap-field` element on the page.
 */
function initAllApFields() {
    document.querySelectorAll('.ap-field:not([data-ap-init])').forEach(el => {
        el.setAttribute('data-ap-init', '1');
        apInitField(el);
    });
}

/* ═══════════════════════════════════════════════════════════════
   10. DATE-TIME PICKER  (DTP)
═══════════════════════════════════════════════════════════════ */

const DTP_MONTHS = ['January','February','March','April','May','June','July','August','September','October','November','December'];
const DTP_WDAYS  = ['Su','Mo','Tu','We','Th','Fr','Sa'];

/** In-memory DTP state keyed by widget id. @type {Object} */
const _dtp = {};
let   _dtpOpen = null;

/**
 * Parse a min/max date value (ISO string or "today").
 * @param {string|null} val
 * @returns {Date|null}
 */
function dtpParseMin(val) {
    if (!val) return null;
    const d = val === 'today' ? new Date() : new Date(val);
    d.setHours(0, 0, 0, 0);
    return d;
}

/**
 * Return a Date with time zeroed.
 * @param {Date} d
 * @returns {Date}
 */
function dtpDateOnly(d) {
    const x = new Date(d);
    x.setHours(0, 0, 0, 0);
    return x;
}

/**
 * Format a Date as "D Mon YYYY".
 * @param {Date|null} d
 * @returns {string}
 */
function dtpFmt(d) {
    if (!d) return '';
    return `${d.getDate()} ${DTP_MONTHS[d.getMonth()].slice(0, 3)} ${d.getFullYear()}`;
}

/**
 * Format a Date as "YYYY-MM-DD" (local, not UTC).
 * @param {Date|null} d
 * @returns {string}
 */
function dtpLocalISO(d) {
    if (!d) return '';
    return `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`;
}

/**
 * Initialise a single `.dtp-field` element.
 * @param {Element} fieldEl
 */
function dtpInit(fieldEl) {
    const id   = fieldEl.dataset.dtpId;
    const mode = fieldEl.dataset.mode || 'date';
    const minD = dtpParseMin(fieldEl.dataset.minDate);
    const maxD = dtpParseMin(fieldEl.dataset.maxDate);

    // purana AbortController hata do — listener cleanly remove hoga
    if (fieldEl._dtpAbort) fieldEl._dtpAbort.abort();
    fieldEl._dtpAbort = new AbortController();
    const signal = fieldEl._dtpAbort.signal;

    _dtp[id] = { mode, minD, maxD, navYear: new Date().getFullYear(), navMonth: new Date().getMonth(), date: null, endDate: null, selecting: false };

    const hiddenInp = document.getElementById(`dtp_val_${id}`);
    const endInp    = document.getElementById(`dtp_end_${id}`);

    if (hiddenInp?.value) {
        const d = new Date(hiddenInp.value); d.setHours(0,0,0,0);
        _dtp[id].date = d; _dtp[id].navYear = d.getFullYear(); _dtp[id].navMonth = d.getMonth();
    } else if (hiddenInp?.hasAttribute('data-default-today') && mode !== 'range') {
        const t = new Date(); t.setHours(0,0,0,0);
        _dtp[id].date = t;
    }

    if (mode === 'range' && endInp?.value) {
        const ed = new Date(endInp.value); ed.setHours(0,0,0,0);
        _dtp[id].endDate = ed;
    }

    fieldEl.addEventListener('click', () => {
        if (_dtpOpen && _dtpOpen !== id) dtpClose(_dtpOpen);
        _dtpOpen === id ? dtpClose(id) : dtpOpen(id);
    }, { signal });

    dtpRender(id);

    // Restore label from pre-existing values
    const s   = _dtp[id];
    const lbl = document.getElementById(`dtp_lbl_${id}`);
    const val = document.getElementById(`dtp_val_${id}`);

    if (mode === 'range') {
        if (endInp?.value) { const ed = new Date(endInp.value); ed.setHours(0,0,0,0); s.endDate = ed; }
        if (s.date && s.endDate && lbl) {
            lbl.textContent = `${dtpFmt(s.date)} – ${dtpFmt(s.endDate)}`;
            lbl.style.cssText = 'color:#1e293b;font-weight:500;';
        }
    } else if (s.date && lbl) {
        lbl.textContent = dtpFmt(s.date);
        lbl.style.cssText = 'color:#1e293b;font-weight:500;';
        if (val) val.value = dtpLocalISO(s.date);
    }
}

/**
 * Open a DTP dropdown.
 * @param {string} id
 */
function dtpOpen(id) {
    _dtpOpen = id;
    document.getElementById(`dtp_dd_${id}`)?.classList.add('open');
    document.querySelectorAll('.ap-dropdown.open').forEach(d => d.classList.remove('open'));
    dtpRender(id);
}

/**
 * Close a DTP dropdown.
 * @param {string} id
 */
function dtpClose(id) {
    document.getElementById(`dtp_dd_${id}`)?.classList.remove('open');
    if (_dtpOpen === id) _dtpOpen = null;
}

/**
 * Re-render the calendar body for the given DTP widget.
 * @param {string} id
 */
function dtpRender(id) {
    const s     = _dtp[id];
    const body  = document.getElementById(`dtp_body_${id}`);
    if (!body) return;

    const today      = dtpDateOnly(new Date());
    const { navYear: year, navMonth: month } = s;
    const firstDay   = new Date(year, month, 1).getDay();
    const daysInMo   = new Date(year, month + 1, 0).getDate();
    const thisYear   = new Date().getFullYear();

    const yearOpts  = Array.from({ length: 7 }, (_, i) => thisYear - 1 + i)
        .map(y => `<option value="${y}"${y === year ? ' selected' : ''}>${y}</option>`).join('');
    const monthOpts = DTP_MONTHS.map((m, i) =>
        `<option value="${i}"${i === month ? ' selected' : ''}>${m}</option>`).join('');

    let cells = '';
    for (let i = 0; i < firstDay; i++) cells += `<div></div>`;
    for (let d = 1; d <= daysInMo; d++) {
        const dt  = dtpDateOnly(new Date(year, month, d));
        const dis = (s.minD && dt < s.minD) || (s.maxD && dt > s.maxD);
        const isTd = dt.getTime() === today.getTime();
        const isSl = s.date    && dt.getTime() === dtpDateOnly(s.date).getTime();
        const isEn = s.endDate && dt.getTime() === dtpDateOnly(s.endDate).getTime();
        const inRg = s.mode === 'range' && s.date && s.endDate && dt > dtpDateOnly(s.date) && dt < dtpDateOnly(s.endDate);

        let cls = 'dtp-day';
        if (dis)  cls += ' disabled';
        if (isTd) cls += ' today';
        if (isSl) cls += ' selected' + (s.mode === 'range' && s.endDate ? ' range-start' : '');
        if (isEn) cls += ' selected range-end';
        if (inRg) cls += ' in-range';
        cells += `<div class="${cls}" data-y="${year}" data-m="${month}" data-d="${d}">${d}</div>`;
    }

    let rangeHint = '';
    if (s.mode === 'range') {
        const hint = !s.date ? 'Select check-in date' : !s.endDate ? 'Now select check-out date' : `${dtpFmt(s.date)} – ${dtpFmt(s.endDate)}`;
        rangeHint  = `<p class="mt-2 text-xs text-center text-slate-400">${hint}</p>`;
    }

    body.innerHTML = `
    <div class="p-4">
      <div class="flex items-center justify-between mb-3 gap-2">
        <button type="button" class="dtp-prev w-7 h-7 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-500">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        </button>
        <div class="flex items-center gap-1.5">
          <select class="dtp-month text-sm font-semibold text-slate-700 bg-slate-50 border border-slate-200 rounded-lg px-2 py-1 outline-none cursor-pointer">${monthOpts}</select>
          <select class="dtp-year  text-sm font-semibold text-slate-700 bg-slate-50 border border-slate-200 rounded-lg px-2 py-1 outline-none cursor-pointer">${yearOpts}</select>
        </div>
        <button type="button" class="dtp-next w-7 h-7 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-500">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        </button>
      </div>
      <div class="grid grid-cols-7 mb-1">
        ${DTP_WDAYS.map(d => `<div class="dtp-day text-xs font-bold text-slate-400 cursor-default">${d}</div>`).join('')}
      </div>
      <div class="grid grid-cols-7 gap-y-0.5">${cells}</div>
      ${rangeHint}
      <div class="flex justify-between items-center mt-3 pt-3 border-t border-slate-100">
        <button type="button" class="dtp-clear btn-outline text-xs py-1.5 px-3">Clear</button>
        <button type="button" class="dtp-done  btn-primary text-xs py-1.5 px-3">Done</button>
      </div>
    </div>`;

    // Navigation
    body.querySelector('.dtp-prev').onclick = () => { s.navMonth === 0 ? (s.navMonth = 11, s.navYear--) : s.navMonth--; dtpRender(id); };
    body.querySelector('.dtp-next').onclick = () => { s.navMonth === 11 ? (s.navMonth = 0, s.navYear++) : s.navMonth++; dtpRender(id); };
    body.querySelector('.dtp-month').onchange = e => { s.navMonth = +e.target.value; dtpRender(id); };
    body.querySelector('.dtp-year').onchange  = e => { s.navYear  = +e.target.value; dtpRender(id); };

    // Clear
    body.querySelector('.dtp-clear').onclick = () => {
        s.date = null; s.endDate = null; s.selecting = false;
        const lbl    = document.getElementById(`dtp_lbl_${id}`);
        const val    = document.getElementById(`dtp_val_${id}`);
        const endVal = document.getElementById(`dtp_end_${id}`);
        if (lbl) { lbl.textContent = s.mode === 'range' ? 'Select dates' : 'Select date'; lbl.style.cssText = 'color:#94a3b8;font-weight:400;'; }
        if (val)    val.value    = '';
        if (endVal) endVal.value = '';
        dtpRender(id);
    };

    // Done
    body.querySelector('.dtp-done').onclick = () => {
        const lbl    = document.getElementById(`dtp_lbl_${id}`);
        const val    = document.getElementById(`dtp_val_${id}`);
        const endVal = document.getElementById(`dtp_end_${id}`);
        if (s.mode === 'range') {
            if (!s.date || !s.endDate) return;
            if (lbl) { lbl.textContent = `${dtpFmt(s.date)} – ${dtpFmt(s.endDate)}`; lbl.style.cssText = 'color:#1e293b;font-weight:500;'; }
            if (val)    val.value    = dtpLocalISO(s.date);
            if (endVal) endVal.value = dtpLocalISO(s.endDate);
            dtpClose(id);
        } else {
            if (s.date) {
                if (lbl) { lbl.textContent = dtpFmt(s.date); lbl.style.cssText = 'color:#1e293b;font-weight:500;'; }
                if (val) val.value = dtpLocalISO(s.date);
            }
            dtpClose(id);
        }
    };

    // Day click
    body.querySelectorAll('.dtp-day[data-d]').forEach(cell => {
        cell.onclick = () => {
            const dt = new Date(+cell.dataset.y, +cell.dataset.m, +cell.dataset.d);
            if (s.mode === 'range') {
                if (!s.date || !s.selecting) { s.date = dt; s.endDate = null; s.selecting = true; }
                else {
                    if (dt < s.date) { s.endDate = s.date; s.date = dt; } else { s.endDate = dt; }
                    s.selecting = false;
                }
            } else {
                s.date = dt;
            }
            dtpRender(id);
        };
    });
}

/**
 * Initialise every `.dtp-field` element on the page.
 */
function initAllDtpFields() {
    document.querySelectorAll('.dtp-field').forEach(el => {
        const id = el.dataset.dtpId;
        if (!id) return;
        // already init hai aur dropdown bhi working hai — skip
        if (el.hasAttribute('data-dtp-init') && document.getElementById('dtp_dd_' + id)) return;
        // state reset karo
        if (_dtp[id]) delete _dtp[id];
        el.setAttribute('data-dtp-init', '1');
        dtpInit(el);
    });
}

/* ═══════════════════════════════════════════════════════════════
   11. TRIP-TYPE TAB TOGGLE
═══════════════════════════════════════════════════════════════ */

/**
 * Wire up `.trip-tab` buttons to update the hidden `#trip_type` input.
 */
function initTripTypeTabs() {
    document.querySelectorAll('.trip-tab').forEach(tab => {
        tab.addEventListener('click', function () {
            document.querySelectorAll('.trip-tab').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            document.getElementById('trip_type').value = this.dataset.trip;
        });
    });
}

/* ═══════════════════════════════════════════════════════════════
   12. HOTEL GUESTS  (HG) DROPDOWN
═══════════════════════════════════════════════════════════════ */

const HG = {
    rooms   : parseInt(document.getElementById('hg_rooms')?.value    || 1),
    adults  : parseInt(document.getElementById('hg_adults')?.value   || 1),
    children: parseInt(document.getElementById('hg_children')?.value || 0),
};
const HG_LIMITS = { rooms: [1, 9], adults: [1, 30], children: [0, 10] };

/** Toggle the Hotel Guests dropdown. */
function toggleHG() {
    const dd = document.getElementById('hgDropdown');
    if (!dd) return;
    dd.classList.toggle('hidden');
    if (!dd.classList.contains('hidden')) {
        document.querySelectorAll('.ap-dropdown.open').forEach(d => d.classList.remove('open'));
        if (typeof _dtpOpen !== 'undefined' && _dtpOpen) dtpClose(_dtpOpen);
    }
}

/** Close the Hotel Guests dropdown. */
function closeHG() {
    document.getElementById('hgDropdown')?.classList.add('hidden');
}

/**
 * Increment / decrement a Hotel Guests counter.
 * @param {'rooms'|'adults'|'children'} type
 * @param {1|-1} delta
 */
function changeHG(type, delta) {
    const [mn, mx] = HG_LIMITS[type];
    HG[type] = Math.min(mx, Math.max(mn, HG[type] + delta));
    document.getElementById(`hg_disp_${type}`).textContent = HG[type];

    const minusBtn = document.getElementById(`btn_${type}_minus`);
    const plusBtn  = document.getElementById(`btn_${type}_plus`);
    if (minusBtn) minusBtn.disabled = HG[type] <= mn;
    if (plusBtn)  plusBtn.disabled  = HG[type] >= mx;

    document.getElementById('hgChildNote')?.classList.toggle('hidden', HG.children === 0);
    updateHGLabel();
}

/** Sync the Hotel Guests summary label and hidden inputs. */
function updateHGLabel() {
    const adultWord = HG.adults   === 1 ? 'adult'    : 'adults';
    const childWord = HG.children === 1 ? 'child'    : 'children';
    const roomWord  = HG.rooms    === 1 ? 'room'     : 'rooms';
    document.getElementById('hgLabel').textContent =
        `${HG.adults} ${adultWord} · ${HG.children} ${childWord} · ${HG.rooms} ${roomWord}`;
    document.getElementById('hg_rooms').value    = HG.rooms;
    document.getElementById('hg_adults').value   = HG.adults;
    document.getElementById('hg_children').value = HG.children;
}

/** Apply Hotel Guests selection and close the dropdown. */
function applyHG() {
    updateHGLabel();
    closeHG();
}

/**
 * Initialise Hotel Guests control button states.
 */
function initHG() {
    ['rooms', 'adults', 'children'].forEach(type => {
        const minusBtn = document.getElementById(`btn_${type}_minus`);
        const plusBtn  = document.getElementById(`btn_${type}_plus`);
        if (minusBtn) minusBtn.disabled = HG[type] <= HG_LIMITS[type][0];
        if (plusBtn)  plusBtn.disabled  = HG[type] >= HG_LIMITS[type][1];
    });
}

/* ═══════════════════════════════════════════════════════════════
   13. MULTI-CITY FLIGHT ROWS
═══════════════════════════════════════════════════════════════ */

const MAX_CITIES = 5;
let multiCityCount = window.multiTotal ? window.multiTotal : 2;

/**
 * Re-render action buttons (Add City / Remove / Search) on the last row.
 */
function refreshButtons() {
    const rows = document.getElementById('addon-rows-container')
        ?.querySelectorAll('.addon-city-row') || [];
    const maxReached = multiCityCount >= MAX_CITIES;

    const SVG = {
        plus  : `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>`,
        x     : `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>`,
        search: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>`,
    };

    rows.forEach((row, i) => {
        const col    = row.querySelector('.addon-action-col');
        const isLast = i === rows.length - 1;

        // Non-last rows — clear action col
        if (!isLast) { col.innerHTML = ''; return; }

        const addBtn = !maxReached
            ? `<button type="button" onclick="addMultiCity()"
                   class="btn btn-secondary border-none flex items-center justify-center gap-1 flex-1"
                   style="height:48px;font-size:14px;font-weight:500;">
                   ${SVG.plus} Add City</button>` : '';

        const removeMobile = multiCityCount >= 3
            ? `<button type="button" onclick="removeMultiCity(this)"
                   class="flex items-center justify-center gap-1 flex-1 bg-gray-200 text-gray-700 hover:bg-gray-300 rounded-lg px-4"
                   style="height:48px;font-size:14px;font-weight:500;">
                   ${SVG.x} Remove</button>` : '';

        const removeDesktop = multiCityCount >= 3
            ? `<button type="button" onclick="removeMultiCity(this)"
                   class="flex items-center justify-center flex-shrink-0 text-base-content/40 hover:text-error transition-colors"
                   style="width:40px;height:40px;border-radius:50%;border:1.5px solid currentColor;background:transparent;cursor:pointer;">
                   ${SVG.x}</button>` : '';

        const searchBtn = `<button type="submit"
                   class="btn btn-primary flex items-center justify-center gap-2"
                   style="height:48px;font-weight:600;font-size:14px;letter-spacing:0.04em;">
                   ${SVG.search} SEARCH</button>`;

        col.innerHTML = `
          <div class="flex flex-col gap-2 w-full">
            <div class="flex items-center gap-2 w-full lg:hidden">
              ${addBtn}${removeMobile}
            </div>
            <div class="w-full lg:hidden">
              <button type="submit" class="btn btn-primary w-full flex items-center justify-center gap-2"
                style="height:48px;font-weight:600;font-size:14px;letter-spacing:0.04em;">
                ${SVG.search} SEARCH</button>
            </div>
            <div class="hidden lg:flex items-center gap-2 w-full">
              ${searchBtn}${addBtn}${removeDesktop}
            </div>
          </div>`;
    });

    // Update flight labels
    rows.forEach((row, i) => {
        const label = row.querySelector('.addon-flight-label');
        if (label) label.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.8 19.2 16 11l3.5-3.5C21 6 21 4 19 2c-2-2-4-2-5.5-.5L10 5 1.8 6.2l2.3 2.3L2 10l2 2 2-2 2 2-2 2 2 2 1.5-3.8 2.3 2.3z"/></svg> Flight ${i + 2}`;
    });
}

/**
 * Clone the first multi-city row and append a new one.
 */
function addMultiCity() {
    if (multiCityCount >= MAX_CITIES) return;

    const container = document.getElementById('addon-rows-container');
    const firstRow  = container.querySelector('.addon-city-row');
    const clone     = firstRow.cloneNode(true);

    multiCityCount++;
    const idx = multiCityCount - 1;
    clone.setAttribute('data-row', idx);

    // Re-index element ids
    clone.querySelectorAll('[id]').forEach(el => {
        const oldId = el.getAttribute('id');
        const newId = oldId.replace(/_\d+$/, '_' + idx);
        el.setAttribute('id', newId);
        clone.querySelectorAll(`[for="${oldId}"]`).forEach(lbl => lbl.setAttribute('for', newId));
    });

    ['data-target','data-id','aria-controls','data-dropdown-target','data-dtp-id'].forEach(attr => {
        clone.querySelectorAll(`[${attr}]`).forEach(el =>
            el.setAttribute(attr, el.getAttribute(attr).replace(/_\d+$/, '_' + idx))
        );
    });

    // Reset display values
    clone.querySelectorAll('.ap-field').forEach(el => {
        el.removeAttribute('data-default');
        el.removeAttribute('data-ap-init');   // allow re-init on clone
    });
    clone.querySelectorAll('.dtp-field').forEach(el => {
        el.removeAttribute('data-dtp-init');  // allow re-init on clone
    });
    clone.querySelectorAll('.ap-display').forEach((el, i) => {
        el.textContent = i === 0 ? 'From' : 'To';
        el.classList.add('text-slate-400');
        el.classList.remove('text-slate-800');
    });
    clone.querySelectorAll('.ap-hidden, .ap-city-hidden').forEach(el => (el.value = ''));
    clone.querySelectorAll('.ap-dropdown').forEach(el => el.classList.remove('open'));
    clone.querySelectorAll('[id^="dtp_lbl_"]').forEach(el => {
        el.textContent = 'Select date'; el.style.color = '#94a3b8'; el.style.fontWeight = '400';
    });
    clone.querySelectorAll('[id^="dtp_val_"]').forEach(el => (el.value = ''));
    clone.querySelectorAll('[id^="dtp_dd_"]').forEach(el => el.classList.remove('open'));
    clone.querySelector('.addon-action-col').innerHTML = '';

    container.appendChild(clone);

    clone.querySelectorAll('.ap-field').forEach(field => {
        if (field.dataset.id) field.dataset.id = field.dataset.id.replace(/_\d+$/, '_' + idx);
        apInitField(field);
    });
    clone.querySelectorAll('.dtp-field').forEach(field => {
        if (field.dataset.dtpId) field.dataset.dtpId = field.dataset.dtpId.replace(/_\d+$/, '_' + idx);
        dtpInit(field);
    });

    refreshButtons();
}

/**
 * Remove the multi-city row that contains the given button.
 * @param {HTMLElement} btn
 */
function removeMultiCity(btn) {
    const container = document.getElementById('addon-rows-container');
    if (container.querySelectorAll('.addon-city-row').length <= 1) return;
    const row = btn.closest('.addon-city-row');
    row.querySelectorAll('.dtp-field').forEach(field => {
        const id = field.dataset.dtpId;
        if (id && window._dtp) delete window._dtp[id];
    });
    row.remove();
    multiCityCount--;
    refreshButtons();
}

/* ═══════════════════════════════════════════════════════════════
   14. FLIGHT FORM VALIDATOR
═══════════════════════════════════════════════════════════════ */

/**
 * Attach JustValidate to `#flightOneWayForm`.
 * Validates that origin ≠ destination.
 * Auto-removes error labels after 3 s.
 */
function initFlightValidator() {
    const form = document.getElementById('flightOneWayForm');
    if (!form || typeof JustValidate === 'undefined') return;

    const validator = new JustValidate('#flightOneWayForm');
    validator
        .addField('#destination', [{
            validator: () => {
                const origin = document.querySelector('[name="origin"]')?.value;
                const dest   = document.querySelector('[name="destination"]')?.value;
                return dest !== origin;
            },
            errorMessage: 'Source and destination cannot be same',
        }])
        .onSuccess(event => event.target.submit())
        .onFail(() => {
            setTimeout(() => {
                document.querySelectorAll('.just-validate-error-label').forEach(el => el.remove());
                document.querySelectorAll('.just-validate-error-field').forEach(el =>
                    el.classList.remove('just-validate-error-field')
                );
            }, 3000);
        });
}

/* ═══════════════════════════════════════════════════════════════
   15. HERO SEARCH BOX TOGGLE
═══════════════════════════════════════════════════════════════ */

/**
 * Wire up the hero section expand / collapse toggle.
 * Exposes `window.toggleHeroSection` for inline onclick usage.
 */
function initHeroToggle() {
    const box       = document.getElementById('addon-rows-container');
    const icon      = document.getElementById('hero-toggle-icon');
    const closedBtn = document.getElementById('collapsed-search-btn');
    if (!box || !icon) return;

    let isOpen = true;

    window.toggleHeroSection = function () {
        isOpen = !isOpen;
        box.style.display    = isOpen ? '' : 'none';
        icon.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(180deg)';
        if (closedBtn) closedBtn.style.display = isOpen ? 'none' : '';
    };
}

/* ═══════════════════════════════════════════════════════════════
   GLOBAL OUTSIDE-CLICK DISMISSAL
═══════════════════════════════════════════════════════════════ */

document.addEventListener('click', (e) => {
    // PAX dropdowns
    document.querySelectorAll("[id$='Wrapper']").forEach(wrapper => {
        if (!e.target.closest('#' + wrapper.id)) {
            wrapper.querySelector("[id$='Dropdown']")?.classList.add('hidden');
        }
    });

    // AP dropdowns
    if (!e.target.closest('.ap-field')) {
        document.querySelectorAll('.ap-dropdown.open').forEach(d => d.classList.remove('open'));
    }

    // DTP
    if (!e.target.isConnected) return;
    if (!e.target.closest('.dtp-field') && _dtpOpen) dtpClose(_dtpOpen);

    // HG dropdown
    if (!e.target.closest('#hgWrapper')) closeHG();
});

/* ═══════════════════════════════════════════════════════════════
   BOOT
═══════════════════════════════════════════════════════════════ */

/**
 * Re-init only the components that Livewire can destroy on DOM patch.
 * Called after every wire:click / wire:model update via Livewire hooks.
 */
function reinitAfterLivewire() {
    initAllApFields();
    initAllDtpFields();
    initTablerIcons();
    initTabs();
    initTripTypeTabs();
    initHG();
    refreshButtons();
    initFlightValidator();
}

document.addEventListener('DOMContentLoaded', () => {
    initLoader();
    initViewToggle();
    initTablerIcons();
    initTabs();
    initFilterSidebar();
    initDestinationsSwiper();
    initAllApFields();
    initAllDtpFields();
    initTripTypeTabs();
    initHG();
    refreshButtons();
    initFlightValidator();
    initHeroToggle();
});

/* ── Livewire 3 hooks ───────────────────────────────────────── */
document.addEventListener('livewire:initialized', () => {
    initLivewireModals();

    // Fires after every Livewire component DOM patch (wire:click, wire:model …)
    Livewire.hook('commit', ({ succeed }) => {
        succeed(() => {
            // requestAnimationFrame = wait for browser to finish painting patched DOM
            requestAnimationFrame(() => reinitAfterLivewire());
        });
    });
});

// Livewire 2 / Alpine morph / full-page navigate fallbacks
document.addEventListener('livewire:update',    () => requestAnimationFrame(reinitAfterLivewire));
document.addEventListener('livewire:load',      () => requestAnimationFrame(reinitAfterLivewire));
document.addEventListener('livewire:navigated', () => requestAnimationFrame(reinitAfterLivewire));

/* ═══════════════════════════════════════════════════════════════
   WINDOW EXPORTS
   Attach every function that Blade / inline scripts call by name.
   Required when app.js is bundled by Vite or loaded as type="module"
   — in both cases the module scope does NOT leak to window automatically.
═══════════════════════════════════════════════════════════════ */

// ── PAX / Travelers ──────────────────────────────────────────
window.initPax             = initPax;
window.toggleTravelers     = toggleTravelers;
window.closeTravelers      = closeTravelers;
window.changePax           = changePax;
window.updateTravelersLabel= updateTravelersLabel;

// ── Airport / Hotel field ────────────────────────────────────
window.apInitField         = apInitField;

// ── Date-Time Picker ─────────────────────────────────────────
window.dtpInit             = dtpInit;
window.dtpOpen             = dtpOpen;
window.dtpClose            = dtpClose;
window.dtpRender           = dtpRender;

// ── Hotel Guests ─────────────────────────────────────────────
window.toggleHG            = toggleHG;
window.closeHG             = closeHG;
window.changeHG            = changeHG;
window.updateHGLabel       = updateHGLabel;
window.applyHG             = applyHG;

// ── Multi-City ───────────────────────────────────────────────
window.addMultiCity        = addMultiCity;
window.removeMultiCity     = removeMultiCity;
window.refreshButtons      = refreshButtons;

// ── Hero toggle ──────────────────────────────────────────────
// window.toggleHeroSection is already set inside initHeroToggle()
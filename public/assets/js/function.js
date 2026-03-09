// tabler icon js
document.addEventListener("DOMContentLoaded", () => {
    // Sabhi <i> tags ko find karein jinme data-tabler attribute hai
    const icons = document.querySelectorAll("i[data-tabler]");

    icons.forEach(async (icon) => {
        const iconName = icon.getAttribute("data-tabler").toLowerCase();

        // Latest v3.0+ URL: Tabler ab icons ko 'outline' ya 'filled' folder mein rakhta hai
        const url = `https://cdn.jsdelivr.net/npm/@tabler/icons@latest/icons/${iconName}.svg`;

        try {
            const response = await fetch(url);

            if (!response.ok) {
                console.warn(`Icon "${iconName}" load nahi ho paya.`);
                return;
            }

            const svgText = await response.text();

            // SVG inject karna
            icon.innerHTML = svgText;

            // SVG Attributes ko dynamic control karna
            const svg = icon.querySelector("svg");
            if (svg) {
                // Size aur Stroke ko attributes se handle karein (Default: 24px, 2 stroke)
                const size = icon.getAttribute("data-size") || "24";
                const stroke = icon.getAttribute("data-stroke") || "1.5";

                svg.setAttribute("width", size);
                svg.setAttribute("height", size);
                svg.setAttribute("stroke-width", stroke);

                // Perfect alignment ke liye styles
                svg.style.display = "inline-block";
                svg.style.verticalAlign = "middle";
                svg.style.stroke = "currentColor"; // Text color match karega
            }
        } catch (err) {
            console.error("Icon fetching error:", err);
        }
    });

    // Tab switching logic for search section
    const mainTabs = document.querySelectorAll(".tabs-border");
    const mainPanels = document.querySelectorAll(".search-tab-content > div");

    if (mainTabs.length > 0 && mainPanels.length > 0) {
        mainTabs.forEach((tab, index) => {
            tab.addEventListener("click", () => {
                // Remove active from all tabs
                mainTabs.forEach((t) => t.classList.remove("active"));
                tab.classList.add("active");

                // Hide all panels and show the selected one
                mainPanels.forEach((panel, pIndex) => {
                    if (index === pIndex) {
                        panel.classList.remove("hidden");
                    } else {
                        panel.classList.add("hidden");
                    }
                });
            });
        });
    }

    const mainPanelsContainer = document.querySelectorAll(
        ".search-tab-content > div",
    );

    mainPanelsContainer.forEach((panel) => {
        const subTabs = panel.querySelectorAll(".tabs");
        const subPanels = panel.querySelectorAll(".subTabs-content > div");

        if (subTabs.length > 0 && subPanels.length > 0) {
            subTabs.forEach((tab, index) => {
                tab.addEventListener("click", () => {
                    // Remove active from only this group
                    subTabs.forEach((t) => t.classList.remove("active"));
                    tab.classList.add("active");

                    // Hide/show only the panels in this main tab
                    subPanels.forEach((subPanel, pIndex) => {
                        subPanel.classList.toggle("hidden", index !== pIndex);
                    });
                });
            });
        }
    });

    // Filter Sidebar Toggle for Mobile
    const openFilterBtn = document.getElementById("open-filter");
    const closeFilterBtn = document.getElementById("close-filter");
    const applyFilterBtn = document.getElementById("apply-filter");
    const filterSidebar = document.getElementById("filter-sidebar");
    const filterBackdrop = document.getElementById("filter-backdrop");

    if (openFilterBtn && filterSidebar && filterBackdrop) {
        const toggleFilter = (isOpen) => {
            if (isOpen) {
                filterSidebar.classList.remove("translate-x-[-100%]");
                filterBackdrop.classList.remove("hidden");
                document.body.style.overflow = "hidden"; // Prevent scrolling
            } else {
                filterSidebar.classList.add("translate-x-[-100%]");
                filterBackdrop.classList.add("hidden");
                document.body.style.overflow = ""; // Restore scrolling
            }
        };

        openFilterBtn.addEventListener("click", () => toggleFilter(true));
        closeFilterBtn?.addEventListener("click", () => toggleFilter(false));
        applyFilterBtn?.addEventListener("click", () => toggleFilter(false));
        filterBackdrop.addEventListener("click", () => toggleFilter(false));
    }
});

var swiper = new Swiper(".DestinationsSlider", {
    loop: true,
    items: 1,
    navigation: {
        nextEl: ".swiper-button-next1",
        prevEl: ".swiper-button-prev1",
    },
    pagination: {
        el: ".swiper-pagination",
        clickable: true,
    },
    breakpoints: {
        0: {
            slidesPerView: 2,
        },
        640: {
            slidesPerView: 4,
        },
        1100: {
            slidesPerView: 5,
        },
        1300: {
            slidesPerView: 8,
        },
    },
});

const counts = { adults: 1, children: 0, infant: 0 };
const min = { adults: 1, children: 0, infant: 0 };
const max = { adults: 9, children: 9, infant: 9 };

function toggleDropdown() {
    const dd = document.getElementById("travelersDropdown");
    const btn = document.getElementById("travelersBtn");
    const ch = document.getElementById("chevron");

    if (dd.classList.contains("hidden")) {
        dd.classList.remove("hidden");
        btn.classList.add("border-blue-400");
        ch.classList.add("rotate-180");
    } else {
        closeDropdown();
    }
}

function closeDropdown() {
    const dd = document.getElementById("travelersDropdown");
    const btn = document.getElementById("travelersBtn");
    const ch = document.getElementById("chevron");

    dd.classList.add("hidden");
    btn.classList.remove("border-blue-400");
    ch.classList.remove("rotate-180");
    updateLabel();
}

function changeCount(type, delta) {
    counts[type] = Math.min(
        max[type],
        Math.max(min[type], counts[type] + delta),
    );
    document.getElementById(`${type}-count`).textContent = counts[type];
    updateLabel();
}

function updateLabel() {
    const total = counts.adults + counts.children + counts.infant;
    const cls =
        document.querySelector('input[name="cabinClass"]:checked')?.value ||
        "Economy";
    const label = `${total} traveler${total !== 1 ? "s" : ""}, ${cls}`;
    const el = document.getElementById("travelersLabel");
    el.textContent = label;
    el.title = label;
}

function handleMoreThan9() {
    const checked = document.getElementById("moreThan9").checked;
    const counters = document.querySelectorAll('[onclick^="changeCount"]');
    counters.forEach((btn) => (btn.disabled = checked));
    if (checked) {
        Object.keys(counts).forEach((k) => {
            counts[k] = k === "adults" ? 10 : 0;
            document.getElementById(`${k}-count`).textContent = counts[k];
        });
        updateLabel();
    }
}

// Close on outside click
document.addEventListener("click", function (e) {
    const wrapper = document.getElementById("travelersWrapper");
    if (!wrapper.contains(e.target)) closeDropdown();
});

//================= Searching =======================

//     * ================================================================
//    ███████╗ ████████╗ ███████╗ ██████╗
//    ██╔════╝    ██╔══╝ ██╔════╝ ██╔══██╗
//    ███████╗    ██║    █████╗   ██████╔╝
//         ╚═╝    ██║    ██╔══╝   ██╔═══╝
//    ███████║    ██║    ███████╗ ██║
//    ╚══════╝    ╚═╝    ╚══════╝ ╚═╝

//    STEP 1 ─ Yahan sirf apni API URLs daalo
//    STEP 2 ─ Response format match karo (neeche dekho)
//    Baaki sab automatically kaam karega!
//    ================================================================ */

const API = {
    airports: "/api/airports", // GET /api/airports?q=del
    cities: "/api/cities", // GET /api/cities?q=jaipur
    carTypes: "/api/car-types", // GET /api/car-types  (static list)
};

/* ================================================================
   BACKEND RESPONSE FORMAT — aapka API yeh format return kare
   ================================================================

   ── Airports (/api/airports?q=del) ──
   {
     "data": [
       {
         "code"    : "DEL",
         "city"    : "Delhi",
         "name"    : "Indira Gandhi International Airport",
         "country" : "India"
       }
     ]
   }

   ── Cities (/api/cities?q=jaipur) ──
   {
     "data": [
       {
         "code"   : "JAI",
         "city"   : "Jaipur",
         "region" : "Rajasthan, India",
         "icon"   : "🏰"             // optional, default pin emoji lagega
       }
     ]
   }

   ── Car Types (/api/car-types) ──
   {
     "data": [
       { "id": "economy", "label": "Economy", "desc": "Hatchback", "icon": "🚗" }
     ]
   }

   ── Laravel example ──
   public function airports(Request $req) {
       $airports = Airport::where('city','like','%'.$req->q.'%')
                          ->orWhere('code','like','%'.$req->q.'%')
                          ->limit(10)->get();
       return response()->json(['data' => $airports]);
   }
   ================================================================ */

/* ================================================================
   DATA CACHE — ek baar fetch hua, dobara request nahi jayegi
   ================================================================ */
const CACHE = {
    airports: null, // null = abhi fetch nahi hua
    cities: null,
    carTypes: null,
};

/* ── TOP codes — backend se pehle yeh dikhao ── */
const TOP_AP_CODES = ["DEL", "BLR", "BOM", "CCU", "JAI", "HYD", "MAA", "DXB"];
const TOP_CITY_CODES = ["JAI", "DEL", "GOA", "MUM", "BLR", "DXB", "PAR", "UDR"];

/* ── Fallback data (jab tak backend se data nahi aata) ── */
const FALLBACK_AIRPORTS = [
    {
        code: "JAI",
        city: "Jaipur",
        name: "Jaipur International Airport",
        country: "India",
    },
    {
        code: "DEL",
        city: "Delhi",
        name: "Indira Gandhi International Airport",
        country: "India",
    },
    {
        code: "BLR",
        city: "Bangalore",
        name: "Bengaluru International Airport",
        country: "India",
    },
    {
        code: "BOM",
        city: "Mumbai",
        name: "Chhatrapati Shivaji International Airport",
        country: "India",
    },
    {
        code: "CCU",
        city: "Kolkata",
        name: "Netaji Subhash Chandra Bose Airport",
        country: "India",
    },
    {
        code: "HYD",
        city: "Hyderabad",
        name: "Rajiv Gandhi International Airport",
        country: "India",
    },
    {
        code: "DXB",
        city: "Dubai",
        name: "Dubai International Airport",
        country: "UAE",
    },
    { code: "LHR", city: "London", name: "Heathrow Airport", country: "UK" },
];
const FALLBACK_CITIES = [
    { code: "JAI", city: "Jaipur", region: "Rajasthan, India", icon: "🏰" },
    { code: "DEL", city: "Delhi", region: "Delhi, India", icon: "🏛️" },
    { code: "GOA", city: "Goa", region: "Goa, India", icon: "🏖️" },
    { code: "MUM", city: "Mumbai", region: "Maharashtra, India", icon: "🌆" },
    { code: "BLR", city: "Bangalore", region: "Karnataka, India", icon: "💻" },
    { code: "DXB", city: "Dubai", region: "UAE", icon: "🏙️" },
    { code: "PAR", city: "Paris", region: "France", icon: "🗼" },
];
const FALLBACK_CAR_TYPES = [
    {
        id: "economy",
        label: "Economy",
        desc: "Hatchback / Small sedan",
        icon: "🚗",
    },
    {
        id: "sedan",
        label: "Sedan",
        desc: "Mid-size comfortable car",
        icon: "🚙",
    },
    { id: "suv", label: "SUV", desc: "7-seater family vehicle", icon: "🛻" },
    {
        id: "luxury",
        label: "Luxury",
        desc: "Premium executive cars",
        icon: "🏎️",
    },
    {
        id: "minibus",
        label: "Minibus",
        desc: "12+ seater group travel",
        icon: "🚐",
    },
];

/* ================================================================
   FETCH HELPER — yahi ek function sab kuch karta hai
   ================================================================ */
async function fetchData(type, query = "") {
    // ── Already cached? Return karo ──
    if (query === "" && CACHE[type]) return CACHE[type];

    const url = query
        ? `${API[type]}?q=${encodeURIComponent(query)}` // search query ke saath
        : API[type]; // full list (car types etc)

    try {
        const res = await fetch(url, {
            headers: {
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
                // Laravel CSRF — agar chahiye toh uncomment karo:
                // 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
            },
        });

        if (!res.ok) throw new Error(`HTTP ${res.status}`);

        const json = await res.json();
        const data = json.data ?? json; // { data: [...] } ya seedha [...]

        // Cache karo (sirf full list, search results nahi)
        if (query === "") CACHE[type] = data;

        return data;
    } catch (err) {
        // ── Backend down / error? Fallback use karo ──
        console.warn(
            `[fetchData] ${type} API failed, using fallback. Error:`,
            err.message,
        );
        const fallbacks = {
            airports: FALLBACK_AIRPORTS,
            cities: FALLBACK_CITIES,
            carTypes: FALLBACK_CAR_TYPES,
        };
        return fallbacks[type] ?? [];
    }
}

/* ── Search with debounce (typing ruke tab fetch ho) ── */
const debounceTimers = {};
function debounce(fn, id, delay = 350) {
    clearTimeout(debounceTimers[id]);
    debounceTimers[id] = setTimeout(fn, delay);
}

/* ================================================================
   SHARED STATE & HELPERS
   ================================================================ */
const STATE = {};

function openDD(ddId, btnId) {
    closeAllDDs(ddId);
    document.getElementById(ddId)?.classList.remove("hidden");
    document.getElementById(btnId)?.classList.add("border-blue-400");
}
function closeDD(ddId, btnId) {
    document.getElementById(ddId)?.classList.add("hidden");
    document.getElementById(btnId)?.classList.remove("border-blue-400");
}
function closeAllDDs(exceptId = "") {
    document.querySelectorAll('[id^="dd_"]').forEach((el) => {
        if (el.id !== exceptId) {
            el.classList.add("hidden");
            document
                .getElementById(el.id.replace("dd_", "btn_"))
                ?.classList.remove("border-blue-400");
        }
    });
    document
        .querySelectorAll('[id^="chv_"]')
        .forEach((c) => c.classList.remove("rotate-180"));
}

/* Loading skeleton */
function showLoading(resultId) {
    const c = document.getElementById(resultId);
    if (!c) return;
    c.innerHTML = `
        <div class="px-4 py-3 space-y-3">
            ${[1, 2, 3]
                .map(
                    () => `
            <div class="flex items-center gap-3 animate-pulse">
                <div class="w-4 h-4 bg-slate-200 rounded flex-shrink-0"></div>
                <div class="flex-1 space-y-1.5">
                    <div class="h-3 bg-slate-200 rounded w-3/4"></div>
                    <div class="h-2.5 bg-slate-100 rounded w-1/2"></div>
                </div>
                <div class="h-2.5 bg-slate-100 rounded w-10"></div>
            </div>`,
                )
                .join("")}
        </div>`;
}

/* ================================================================
   COMPONENT 1 — AIRPORT SEARCH
   ================================================================ */
function buildAirportSearch(el) {
    const id = el.dataset.id;
    const lbl = el.dataset.label || "Airport";
    const ph = el.dataset.placeholder || "Search airport";
    const def = el.dataset.defaultCode || "";

    STATE[id] = def
        ? FALLBACK_AIRPORTS.find((a) => a.code === def) || null
        : null;
    const display = STATE[id] ? `${STATE[id].code} – ${STATE[id].city}` : ph;
    const color = STATE[id] ? "text-slate-800" : "text-slate-400";

    el.classList.add("relative");
    el.innerHTML = `
        <div id="btn_${id}" onclick="AP.open('${id}')"
             class="flex items-center gap-3 bg-slate-50 p-3 rounded-lg border border-slate-200 hover:border-blue-400 transition cursor-pointer h-full">
            <svg class="w-5 h-5 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24">
                <path d="M21.485 12A59.768 59.768 0 003.27 3.126L6 12m0 0l-2.73 8.874A59.77 59.77 0 0021.485 12M6 12h7.5"
                      stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <div class="flex flex-col min-w-0 flex-1">
                <span class="text-xs text-slate-500">${lbl}</span>
                <span id="lbl_${id}" class="text-sm font-medium truncate ${color}">${display}</span>
            </div>
        </div>
        <div id="dd_${id}" class="drop-anim hidden absolute top-full left-0 mt-2 w-80 bg-white rounded-1xl shadow-2xl border border-slate-100 z-50 overflow-hidden">
            <div class="flex items-center gap-2 px-4 py-3 border-b border-slate-100">
                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
                </svg>
                <input id="inp_${id}" type="text" placeholder="${ph}"
                       oninput="AP.onType('${id}')"
                       class="flex-1 text-sm text-slate-800 outline-none bg-transparent placeholder-slate-400 border-none"/>
                <button onclick="AP.clearInp('${id}')" class="text-slate-300 hover:text-slate-500 text-xl leading-none">&times;</button>
            </div>
            <div id="res_${id}" class="thin-scroll max-h-64 overflow-y-auto"></div>
        </div>`;
}

const AP = {
    async open(id) {
        openDD(`dd_${id}`, `btn_${id}`);
        showLoading(`res_${id}`);
        setTimeout(() => document.getElementById(`inp_${id}`)?.focus(), 40);
        // Top cities fetch karo
        const all = await fetchData("airports");
        const top = all.filter((a) => TOP_AP_CODES.includes(a.code));
        AP.render(id, top.length ? top : all.slice(0, 8), "Top Cities");
    },
    onType(id) {
        const q = document.getElementById(`inp_${id}`)?.value.trim();
        if (!q) {
            AP.open(id);
            return;
        }
        showLoading(`res_${id}`);
        // Debounce — 350ms ruke phir fetch karo
        debounce(async () => {
            const results = await fetchData("airports", q);
            AP.render(id, results, "");
        }, id);
    },
    render(id, list, header) {
        const c = document.getElementById(`res_${id}`);
        if (!c) return;
        if (!list.length) {
            c.innerHTML = `<p class="px-4 py-5 text-center text-sm text-slate-400">No airports found</p>`;
            return;
        }
        c.innerHTML =
            (header
                ? `<div class="px-4 py-2 text-xs font-semibold text-slate-500 bg-slate-50 border-b sticky top-0">${header}</div>`
                : "") +
            list
                .map(
                    (a) => `
<div onclick="AP.select('${id}','${a.code}','${a.city.replace(/'/g, "\\'")}','${(a.name || "").replace(/'/g, "\\'")}','${a.country || ""}')"
     class="flex items-start gap-3 px-4 py-3 hover:bg-blue-50 cursor-pointer border-b border-slate-50 last:border-0 transition group">
    <div class="w-7 h-7 rounded-md bg-slate-100 group-hover:bg-blue-100 flex items-center justify-center flex-shrink-0 mt-0.5 transition">
        <svg class="w-3.5 h-3.5 text-slate-500 group-hover:text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/>
        </svg>
    </div>
    <div class="flex-1 min-w-0">
        <p class="text-sm font-bold text-slate-800 group-hover:text-blue-600 transition leading-5">
            ${a.city}<span class="font-normal text-slate-500">(${a.code})</span>
        </p>
        <p class="text-xs text-slate-400 truncate leading-4">${a.name || ""}</p>
    </div>
    <span class="text-xs text-slate-400 bg-slate-100 rounded px-1.5 py-0.5 flex-shrink-0 mt-0.5 font-medium">${a.country || ""}</span>
</div>`,
                )
                .join("");
    },
    select(id, code, city, name, country) {
        STATE[id] = { code, city, name, country };
        const lbl = document.getElementById(`lbl_${id}`);
        lbl.textContent = `${code} – ${city}`;
        lbl.className = lbl.className.replace(
            "text-slate-400",
            "text-slate-800",
        );
        closeDD(`dd_${id}`, `btn_${id}`);
        if (document.getElementById(`inp_${id}`))
            document.getElementById(`inp_${id}`).value = "";
    },
    clearInp(id) {
        document.getElementById(`inp_${id}`).value = "";
        AP.open(id);
        document.getElementById(`inp_${id}`).focus();
    },
};

/* ================================================================
   COMPONENT 2 — CITY / HOTEL / CAR LOCATION SEARCH
   ================================================================ */
function buildCitySearch(el) {
    // city search
    const id = el.dataset.id;
    const lbl = el.dataset.label || "Destination";
    const ph = el.dataset.placeholder || "Search city";
    const def = el.dataset.defaultCode || "";
    const icon = el.dataset.icon === "car" ? "car" : "pin";

    STATE[id] = def
        ? FALLBACK_CITIES.find((c) => c.code === def) || null
        : null;
    const display = STATE[id]
        ? `${STATE[id].icon || "📍"} ${STATE[id].city}`
        : ph;
    const color = STATE[id] ? "text-slate-800" : "text-slate-400";

    el.classList.add("relative", "h-full");
    const svgIcon =
        icon === "car"
            ? `<svg class="w-5 h-5 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="#94A3B8" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg>`
            : `<svg class="w-5 h-5 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="#94A3B8" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>`;

    el.innerHTML = `
        <div id="btn_${id}" onclick="CS.open('${id}')"
             class="flex items-center gap-3 bg-slate-50 p-3 rounded-lg border border-slate-200 hover:border-blue-400 transition cursor-pointer h-full">
            ${svgIcon}
            <div class="flex flex-col min-w-0 flex-1">
                <span class="text-xs text-slate-500">${lbl}</span>
                <span id="lbl_${id}" class="text-sm font-medium truncate ${color}">${display}</span>
            </div>
        </div>
        <div id="dd_${id}" class="drop-anim hidden absolute top-full left-0 mt-2 w-80 bg-white rounded-1xl shadow-2xl border border-slate-100 z-50 overflow-hidden">
            <div class="flex items-center gap-2 px-4 py-3 border-b border-slate-100">
                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/></svg>
                <input id="inp_${id}" type="text" placeholder="${ph}"
                       oninput="CS.onType('${id}')"
                       class="flex-1 text-sm text-slate-800 outline-none bg-transparent placeholder-slate-400 border-none"/>
                <button onclick="CS.clearInp('${id}')" class="text-slate-300 hover:text-slate-500 text-xl leading-none">&times;</button>
            </div>
            <div id="res_${id}" class="thin-scroll max-h-64 overflow-y-auto"></div>
        </div>`;
}

const CS = {
    async open(id) {
        openDD(`dd_${id}`, `btn_${id}`);
        showLoading(`res_${id}`);
        setTimeout(() => document.getElementById(`inp_${id}`)?.focus(), 40);
        const all = await fetchData("cities");
        const top = all.filter((c) => TOP_CITY_CODES.includes(c.code));
        CS.render(
            id,
            top.length ? top : all.slice(0, 8),
            "Popular Destinations",
        );
    },
    onType(id) {
        const q = document.getElementById(`inp_${id}`)?.value.trim();
        if (!q) {
            CS.open(id);
            return;
        }
        showLoading(`res_${id}`);
        debounce(async () => {
            const results = await fetchData("cities", q);
            CS.render(id, results, "");
        }, id);
    },
    render(id, list, header) {
        const c = document.getElementById(`res_${id}`);
        if (!c) return;
        if (!list.length) {
            c.innerHTML = `<p class="px-4 py-5 text-center text-sm text-slate-400">No destinations found</p>`;
            return;
        }
        c.innerHTML =
            (header
                ? `<div class="px-4 py-2 text-xs font-semibold text-slate-500 bg-slate-50 border-b sticky top-0">${header}</div>`
                : "") +
            list
                .map(
                    (ct) => `
            <div onclick="CS.select('${id}','${ct.code}','${ct.city.replace(/'/g, "\\'")}','${(ct.region || "").replace(/'/g, "\\'")}','${ct.icon || "📍"}')"
                 class="flex items-center gap-3 px-4 py-3 hover:bg-blue-50 cursor-pointer border-b border-slate-50 last:border-0 transition group">
                <span class="text-xl w-7 text-center flex-shrink-0">${ct.icon || "📍"}</span>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-slate-800 group-hover:text-blue-600">${ct.city}</p>
                    <p class="text-xs text-slate-400">${ct.region || ""}</p>
                </div>
            </div>`,
                )
                .join("");
    },
    select(id, code, city, region, icon) {
        STATE[id] = { code, city, region, icon };
        const lbl = document.getElementById(`lbl_${id}`);
        lbl.textContent = `${icon} ${city}`;
        lbl.className = lbl.className.replace(
            "text-slate-400",
            "text-slate-800",
        );
        closeDD(`dd_${id}`, `btn_${id}`);
        if (document.getElementById(`inp_${id}`))
            document.getElementById(`inp_${id}`).value = "";
    },
    clearInp(id) {
        document.getElementById(`inp_${id}`).value = "";
        CS.open(id);
        document.getElementById(`inp_${id}`).focus();
    },
};

/* ================================================================
   COMPONENT 3 — TRAVELERS & CLASS
   ================================================================ */
function buildTravelers(el) {
    const id = el.dataset.id;
    STATE[id] = { adults: 1, children: 0, infant: 0, cls: "Economy" };
    el.classList.add("relative");
    el.innerHTML = `
        <div id="btn_${id}" onclick="PAX.toggle('${id}')"
             class="flex items-center gap-3 bg-slate-50 p-3 rounded-lg border border-slate-200 hover:border-blue-400 transition cursor-pointer h-full select-none">
            <svg class="w-5 h-5 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24">
                <path d="M16.5 6C16.5 8.485 14.485 10.5 12 10.5S7.5 8.485 7.5 6 9.515 1.5 12 1.5 16.5 3.515 16.5 6ZM3 23.25C3 17.451 7.701 12.75 12 12.75S21 17.451 21 23.25"
                      stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round"/>
            </svg>
            <div class="flex flex-col flex-1 min-w-0">
                <span class="text-xs text-slate-500">Travelers & Class</span>
                <span id="lbl_${id}" class="text-sm font-medium text-slate-800">1 traveler, Economy</span>
            </div>
            <svg id="chv_${id}" class="w-4 h-4 text-slate-400 transition-transform flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
        </div>
        <div id="dd_${id}" class="drop-anim hidden absolute top-full right-0 mt-2 w-72 bg-white rounded-2xl shadow-2xl border border-slate-100 z-50 p-4">
            ${[
                "Adults|12+ Years|adults|1",
                "Children|2-12 Years|children|0",
                "Infant|0-2 Years|infant|0",
            ]
                .map((r) => {
                    const [name, sub, key, def] = r.split("|");
                    return `<div class="flex items-center justify-between mb-4">
                    <div><p class="text-sm font-semibold text-slate-800">${name}</p><p class="text-xs text-slate-400">${sub}</p></div>
                    <div class="flex items-center gap-3">
                        <button onclick="PAX.change('${id}','${key}',-1)" class="w-8 h-8 rounded-full border border-slate-300 flex items-center justify-center text-slate-600 hover:bg-slate-100 text-lg">−</button>
                        <span id="${id}_${key}" class="w-5 text-center text-sm font-semibold text-slate-800">${def}</span>
                        <button onclick="PAX.change('${id}','${key}',1)" class="w-8 h-8 rounded-full border border-slate-300 flex items-center justify-center text-slate-600 hover:bg-slate-100 text-lg">+</button>
                    </div>
                </div>`;
                })
                .join("")}
            <div class="border-t border-slate-100 pt-3 mb-3 space-y-2">
                ${["Economy", "Premium Economy", "Business", "First Class"]
                    .map(
                        (c) => `
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="radio" name="cls_${id}" value="${c}" ${c === "Economy" ? "checked" : ""} onchange="PAX.updateLabel('${id}')" class="accent-blue-500">
                    <span class="text-sm text-slate-700">${c}</span>
                </label>`,
                    )
                    .join("")}
            </div>
            <button onclick="PAX.close('${id}')" class="w-full py-2.5 rounded-xl bg-blue-500 hover:bg-blue-600 text-white text-sm font-semibold transition">Done</button>
        </div>`;
}
const PAX = {
    MIN: { adults: 1, children: 0, infant: 0 },
    MAX: { adults: 9, children: 9, infant: 9 },
    toggle(id) {
        const isOpen = !document
            .getElementById(`dd_${id}`)
            .classList.contains("hidden");
        if (isOpen) {
            PAX.close(id);
            return;
        }
        openDD(`dd_${id}`, `btn_${id}`);
        document.getElementById(`chv_${id}`)?.classList.add("rotate-180");
    },
    close(id) {
        closeDD(`dd_${id}`, `btn_${id}`);
        document.getElementById(`chv_${id}`)?.classList.remove("rotate-180");
    },
    change(id, key, delta) {
        const s = STATE[id];
        s[key] = Math.min(
            this.MAX[key],
            Math.max(this.MIN[key], s[key] + delta),
        );
        document.getElementById(`${id}_${key}`).textContent = s[key];
        PAX.updateLabel(id);
    },
    updateLabel(id) {
        const s = STATE[id];
        const cls =
            document.querySelector(`input[name="cls_${id}"]:checked`)?.value ||
            "Economy";
        s.cls = cls;
        const t = s.adults + s.children + s.infant;
        document.getElementById(`lbl_${id}`).textContent =
            `${t} traveler${t > 1 ? "s" : ""}, ${cls}`;
    },
};

/* ================================================================
   COMPONENT 4 — GUESTS & ROOMS
   ================================================================ */
function buildGuests(el) {
    const id = el.dataset.id;
    STATE[id] = { adults: 2, children: 0, rooms: 1 };
    el.classList.add("relative");
    el.innerHTML = `
        <div id="btn_${id}" onclick="GR.toggle('${id}')"
             class="flex items-center gap-3 bg-slate-50 p-3 rounded-lg border border-slate-200 hover:border-blue-400 transition cursor-pointer h-full select-none">
            <svg class="w-5 h-5 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="#94A3B8" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
            <div class="flex flex-col flex-1 min-w-0">
                <span class="text-xs text-slate-500">Guests & Rooms</span>
                <span id="lbl_${id}" class="text-sm font-medium text-slate-800">2 Adults, 1 Room</span>
            </div>
            <svg id="chv_${id}" class="w-4 h-4 text-slate-400 transition-transform flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
        </div>
        <div id="dd_${id}" class="drop-anim hidden absolute top-full right-0 mt-2 w-64 bg-white rounded-2xl shadow-2xl border border-slate-100 z-50 p-4">
            ${[
                ["Adults", "adults", "18+ Years", 2, 1, 9],
                ["Children", "children", "0-17 Years", 0, 0, 9],
                ["Rooms", "rooms", "", 1, 1, 9],
            ]
                .map(
                    ([name, key, sub, def, mn, mx]) => `
            <div class="flex items-center justify-between mb-4 last:mb-0">
                <div><p class="text-sm font-semibold text-slate-800">${name}</p>${sub ? `<p class="text-xs text-slate-400">${sub}</p>` : ""}</div>
                <div class="flex items-center gap-3">
                    <button onclick="GR.change('${id}','${key}',-1,${mn})" class="w-8 h-8 rounded-full border border-slate-300 flex items-center justify-center text-slate-600 hover:bg-slate-100 text-lg">−</button>
                    <span id="${id}_${key}" class="w-5 text-center text-sm font-semibold text-slate-800">${def}</span>
                    <button onclick="GR.change('${id}','${key}',1,${mx})" class="w-8 h-8 rounded-full border border-slate-300 flex items-center justify-center text-slate-600 hover:bg-slate-100 text-lg">+</button>
                </div>
            </div>`,
                )
                .join("")}
            <button onclick="GR.close('${id}')" class="w-full mt-4 py-2.5 rounded-xl bg-blue-500 hover:bg-blue-600 text-white text-sm font-semibold transition">Done</button>
        </div>`;
}
const GR = {
    toggle(id) {
        const isOpen = !document
            .getElementById(`dd_${id}`)
            .classList.contains("hidden");
        if (isOpen) {
            GR.close(id);
            return;
        }
        openDD(`dd_${id}`, `btn_${id}`);
        document.getElementById(`chv_${id}`)?.classList.add("rotate-180");
    },
    close(id) {
        closeDD(`dd_${id}`, `btn_${id}`);
        document.getElementById(`chv_${id}`)?.classList.remove("rotate-180");
    },
    change(id, key, delta, min) {
        const s = STATE[id];
        const max = 9;
        s[key] = Math.min(max, Math.max(min, s[key] + delta));
        document.getElementById(`${id}_${key}`).textContent = s[key];
        GR.updateLabel(id);
    },
    updateLabel(id) {
        const s = STATE[id];
        const kids = s.children
            ? `, ${s.children} Child${s.children > 1 ? "ren" : ""}`
            : "";
        document.getElementById(`lbl_${id}`).textContent =
            `${s.adults} Adult${s.adults > 1 ? "s" : ""}${kids}, ${s.rooms} Room${s.rooms > 1 ? "s" : ""}`;
    },
};

/* ================================================================
   COMPONENT 5 — CAR TYPE  (backend se bhi aa sakta hai)
   ================================================================ */
function buildCarType(el) {
    const id = el.dataset.id;
    STATE[id] = null;
    el.classList.add("relative");
    el.innerHTML = `
        <div id="btn_${id}" onclick="CT.open('${id}')"
             class="flex items-center gap-3 bg-slate-50 p-3 rounded-lg border border-slate-200 hover:border-blue-400 transition cursor-pointer h-full select-none">
            <svg class="w-5 h-5 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="#94A3B8" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg>
            <div class="flex flex-col flex-1 min-w-0">
                <span class="text-xs text-slate-500">Car Type</span>
                <span id="lbl_${id}" class="text-sm font-medium text-slate-400">Any Car Type</span>
            </div>
            <svg id="chv_${id}" class="w-4 h-4 text-slate-400 transition-transform flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
        </div>
        <div id="dd_${id}" class="drop-anim hidden absolute top-full right-0 mt-2 w-64 bg-white rounded-2xl shadow-2xl border border-slate-100 z-50 overflow-hidden">
            <div class="px-4 py-2 text-xs font-semibold text-slate-500 bg-slate-50 border-b">Select Car Type</div>
            <div id="res_${id}" class="thin-scroll max-h-64 overflow-y-auto"></div>
        </div>`;
}
const CT = {
    async open(id) {
        const isOpen = !document
            .getElementById(`dd_${id}`)
            .classList.contains("hidden");
        if (isOpen) {
            CT.close(id);
            return;
        }
        openDD(`dd_${id}`, `btn_${id}`);
        document.getElementById(`chv_${id}`)?.classList.add("rotate-180");
        showLoading(`res_${id}`);
        const types = await fetchData("carTypes");
        CT.render(id, types);
    },
    render(id, list) {
        const c = document.getElementById(`res_${id}`);
        if (!c) return;
        c.innerHTML = list
            .map(
                (ct) => `
            <div onclick="CT.select('${id}','${ct.id}','${ct.label}','${ct.icon || "🚗"}')"
                 class="flex items-center gap-3 px-4 py-3 hover:bg-blue-50 cursor-pointer border-b border-slate-50 last:border-0 transition group">
                <span class="text-2xl">${ct.icon || "🚗"}</span>
                <div>
                    <p class="text-sm font-bold text-slate-800 group-hover:text-blue-600">${ct.label}</p>
                    <p class="text-xs text-slate-400">${ct.desc || ""}</p>
                </div>
            </div>`,
            )
            .join("");
    },
    close(id) {
        closeDD(`dd_${id}`, `btn_${id}`);
        document.getElementById(`chv_${id}`)?.classList.remove("rotate-180");
    },
    select(id, typeId, label, icon) {
        STATE[id] = { id: typeId, label, icon };
        const lbl = document.getElementById(`lbl_${id}`);
        lbl.textContent = `${icon} ${label}`;
        lbl.className = lbl.className.replace(
            "text-slate-400",
            "text-slate-800",
        );
        CT.close(id);
    },
};

/* ================================================================
   TAB SWITCHER
   ================================================================ */
function switchTab(name, btn) {
    document
        .querySelectorAll(".tab-panel")
        .forEach((p) => p.classList.add("hidden"));
    document.getElementById(`tab_${name}`)?.classList.remove("hidden");
    document
        .querySelectorAll(".tab-btn")
        .forEach((b) => b.classList.remove("active"));
    btn.classList.add("active");
    closeAllDDs();
}
function toggleDiffDropoff() {
    document
        .getElementById("cr_dropoff_wrap")
        ?.classList.toggle(
            "hidden",
            !document.getElementById("diffDropoff").checked,
        );
}

/* ================================================================
   INIT
   ================================================================ */
document.addEventListener("DOMContentLoaded", () => {
    document
        .querySelectorAll("[data-airport-search]")
        .forEach(buildAirportSearch);
    document.querySelectorAll("[data-city-search]").forEach(buildCitySearch);
    document.querySelectorAll("[data-travelers]").forEach(buildTravelers);
    document.querySelectorAll("[data-guests]").forEach(buildGuests);
    document.querySelectorAll("[data-car-type]").forEach(buildCarType);
});

document.addEventListener("click", (e) => {
    if (
        !e.target.closest(
            "[data-airport-search],[data-city-search],[data-travelers],[data-guests],[data-car-type]",
        )
    ) {
        closeAllDDs();
    }
});


//========== Date Time Picker -----============



const DTP_STATE = {};  // { id: { date, endDate, time, hour, min, ampm } }
const DTP_OPEN  = { id: null };

const MONTHS = ['January','February','March','April','May','June',
                'July','August','September','October','November','December'];
const DAYS   = ['Su','Mo','Tu','We','Th','Fr','Sa'];

/* ── Parse date options ── */
function parseLimit(val) {
    if (!val) return null;
    if (val === 'today') {
        const t = new Date(); t.setHours(0,0,0,0); return t;
    }
    const d = new Date(val); d.setHours(0,0,0,0); return d;
}

function dateOnly(d) { const x=new Date(d); x.setHours(0,0,0,0); return x; }

/* ── Format display ── */
function fmtDate(d) {
    if (!d) return null;
    return `${d.getDate()} ${MONTHS[d.getMonth()].slice(0,3)} ${d.getFullYear()}`;
}
function fmtTime(s) {
    if (!s) return '';
    const { hour, min, ampm, fmt } = s;
    if (fmt === '24') return `${String(hour).padStart(2,'0')}:${String(min).padStart(2,'0')}`;
    return `${String(hour).padStart(2,'0')}:${String(min).padStart(2,'0')} ${ampm}`;
}

/* ── Build trigger button HTML ── */
function buildTrigger(el) {
    const id   = el.dataset.id;
    const mode = el.dataset.mode || 'date';
    const lbl  = el.dataset.label || 'Select Date';
    const ph   = el.dataset.placeholder || (mode==='time' ? 'Select Time' : mode==='range' ? 'Select Dates' : 'Select Date');

    const calIcon = `<svg class="w-5 h-5 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24">
        <rect x="1.5" y="4.5" width="21" height="18" rx="1.5" stroke="#94A3B8" stroke-width="1.5"/>
        <path d="M1.5 9.5H22.5M7.5 0.75V6M16.5 0.75V6" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round"/>
    </svg>`;
    const clockIcon = `<svg class="w-5 h-5 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24">
        <circle cx="12" cy="12" r="9.75" stroke="#94A3B8" stroke-width="1.5"/>
        <path d="M12 7.5V12l3 2" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round"/>
    </svg>`;
    const icon = mode === 'time' ? clockIcon : calIcon;

    // Tumhare existing grid item format ke andar fit hone ke liye
    // same classes jaise airport-search aur travelers component
    el.classList.add('relative', 'h-full');
    el.innerHTML = `
        <div id="dtp_btn_${id}" onclick="DTP.toggle('${id}')"
             class="flex items-center gap-3 bg-slate-50 p-3 rounded-lg border border-slate-200 hover:border-blue-400 transition cursor-pointer h-full">
            ${icon}
            <div class="flex flex-col min-w-0 flex-1">
                <span class="text-xs text-slate-500 leading-4">${lbl}</span>
                <span id="dtp_lbl_${id}" class="text-sm font-medium truncate w-full block" style="color:#94a3b8">${ph}</span>
            </div>
        </div>
        <div id="dtp_dd_${id}" class="dtp-drop hidden absolute top-full left-0 mt-1 z-50 bg-white rounded-xl shadow-xl border border-slate-100 overflow-hidden" style="min-width:20rem">
            <div id="dtp_body_${id}"></div>
        </div>`;
}

/* ── Main Controller ── */
const DTP = {

    init(el) {
        const id   = el.dataset.id;
        const mode = el.dataset.mode || 'date';
        const fmt  = el.dataset.timeFormat || '12';
        const min  = parseLimit(el.dataset.minDate);
        const max  = parseLimit(el.dataset.maxDate);

        DTP_STATE[id] = {
            mode, fmt, min, max,
            navYear  : new Date().getFullYear(),
            navMonth : new Date().getMonth(),
            date     : null,
            endDate  : null,
            rangeSelecting: false,
            hour: fmt==='12' ? 12 : 0,
            min : 0,
            ampm: 'AM',
            tab : mode === 'time' ? 'time' : 'date',   // active tab
        };
        buildTrigger(el);
    },

    toggle(id) {
        if (DTP_OPEN.id && DTP_OPEN.id !== id) DTP.close(DTP_OPEN.id);
        const dd = document.getElementById(`dtp_dd_${id}`);
        if (dd.classList.contains('hidden')) {
            DTP.open(id);
        } else {
            DTP.close(id);
        }
    },

    open(id) {
        DTP_OPEN.id = id;
        const dd  = document.getElementById(`dtp_dd_${id}`);
        const btn = document.getElementById(`dtp_btn_${id}`);
        const chv = document.getElementById(`dtp_chv_${id}`);
        dd.classList.remove('hidden');
        btn.classList.add('open');
        chv?.classList.add('rotate-180');
        DTP.render(id);
    },

    close(id) {
        const dd  = document.getElementById(`dtp_dd_${id}`);
        const btn = document.getElementById(`dtp_btn_${id}`);
        const chv = document.getElementById(`dtp_chv_${id}`);
        dd?.classList.add('hidden');
        btn?.classList.remove('open');
        chv?.classList.remove('rotate-180');
        if (DTP_OPEN.id === id) DTP_OPEN.id = null;
    },

    /* ── Main render dispatcher ── */
    render(id) {
        const s   = DTP_STATE[id];
        const body= document.getElementById(`dtp_body_${id}`);

        if (s.mode === 'time') {
            body.innerHTML = DTP.renderTimePicker(id);
            DTP.syncDrums(id);
            return;
        }

        // Tabs for datetime mode
        let tabs = '';
        if (s.mode === 'datetime') {
            tabs = `<div class="flex gap-1 p-3 pb-0 border-b border-slate-100 mb-0">
                <button class="dtp-tab ${s.tab==='date'?'active':''}" onclick="DTP.setTab('${id}','date')">📅 Date</button>
                <button class="dtp-tab ${s.tab==='time'?'active':''}" onclick="DTP.setTab('${id}','time')">🕐 Time</button>
            </div>`;
        }

        const calOrTime = s.tab === 'time'
            ? DTP.renderTimePicker(id)
            : DTP.renderCalendar(id);

        body.innerHTML = tabs + calOrTime;

        if (s.tab === 'time') DTP.syncDrums(id);
    },

    setTab(id, tab) {
        DTP_STATE[id].tab = tab;
        DTP.render(id);
    },

    /* ── Calendar ── */
    renderCalendar(id) {
        const s = DTP_STATE[id];
        const year  = s.navYear;
        const month = s.navMonth;
        const today = dateOnly(new Date());

        // Month grid
        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month+1, 0).getDate();

        // Year select options (-1 to +5 years)
        const thisYear = new Date().getFullYear();
        const yearOpts = Array.from({length:7},(_,i)=>thisYear-1+i)
            .map(y=>`<option value="${y}" ${y===year?'selected':''}>${y}</option>`).join('');

        const monthOpts = MONTHS.map((m,i)=>
            `<option value="${i}" ${i===month?'selected':''}>${m}</option>`).join('');

        // Build day cells
        let cells = '';
        // Empty slots before first day
        for(let i=0;i<firstDay;i++) cells+=`<div></div>`;

        for(let d=1;d<=daysInMonth;d++){
            const dt = dateOnly(new Date(year,month,d));
            const isDisabled = (s.min && dt < s.min) || (s.max && dt > s.max);
            const isToday    = dt.getTime()===today.getTime();
            const isSelected = s.date && dt.getTime()===dateOnly(s.date).getTime();
            const isEndSel   = s.endDate && dt.getTime()===dateOnly(s.endDate).getTime();
            const inRange    = s.mode==='range' && s.date && s.endDate
                && dt > dateOnly(s.date) && dt < dateOnly(s.endDate);

            let cls = 'dtp-day';
            if (isDisabled) cls += ' disabled';
            if (isToday)    cls += ' today';
            if (isSelected) cls += ' selected' + (s.mode==='range' && s.endDate ? ' range-start':'');
            if (isEndSel)   cls += ' selected range-end';
            if (inRange)    cls += ' in-range';

            cells += `<div class="${cls}" onclick="DTP.selectDay('${id}',${year},${month},${d})">${d}</div>`;
        }

        return `
        <div class="p-4">
            <!-- Nav Header -->
            <div class="flex items-center justify-between mb-3 gap-2">
                <button onclick="DTP.prevMonth('${id}')"
                        class="w-7 h-7 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-500 transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <div class="flex items-center gap-1.5 flex-1 justify-center">
                    <select onchange="DTP.setMonth('${id}',this.value)"
                            class="text-sm font-semibold text-slate-700 bg-slate-50 border border-slate-200 rounded-lg px-2 py-1 outline-none cursor-pointer hover:border-blue-400 transition">
                        ${monthOpts}
                    </select>
                    <select onchange="DTP.setYear('${id}',this.value)"
                            class="text-sm font-semibold text-slate-700 bg-slate-50 border border-slate-200 rounded-lg px-2 py-1 outline-none cursor-pointer hover:border-blue-400 transition">
                        ${yearOpts}
                    </select>
                </div>
                <button onclick="DTP.nextMonth('${id}')"
                        class="w-7 h-7 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-500 transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>

            <!-- Day Headers -->
            <div class="grid grid-cols-7 mb-1">
                ${DAYS.map(d=>`<div class="dtp-day text-xs font-bold text-slate-400 cursor-default">${d}</div>`).join('')}
            </div>

            <!-- Day Grid -->
            <div class="grid grid-cols-7 gap-y-0.5">${cells}</div>

            <!-- Range hint -->
            ${s.mode==='range' ? `
            <div class="mt-3 text-xs text-slate-400 text-center">
                ${!s.date ? 'Select check-in date' : !s.endDate ? 'Now select check-out date' : `${fmtDate(s.date)} → ${fmtDate(s.endDate)}`}
            </div>` : ''}

            <!-- Footer -->
            <div class="flex justify-between items-center mt-3 pt-3 border-t border-slate-100">
                <button onclick="DTP.clear('${id}')" class="btn-outline text-xs py-1.5 px-3">Clear</button>
                <div class="flex gap-2">
                    ${s.mode==='datetime' ? `<button onclick="DTP.setTab('${id}','time')" class="btn-outline text-xs py-1.5 px-3">Next: Time →</button>` : ''}
                    <button onclick="DTP.confirm('${id}')" class="btn-primary text-xs py-1.5 px-3">Done</button>
                </div>
            </div>
        </div>`;
    },

    /* ── Time Picker ── */
    renderTimePicker(id) {
        const s   = DTP_STATE[id];
        const is24= s.fmt === '24';

        const hours = is24
            ? Array.from({length:24},(_,i)=>i)
            : Array.from({length:12},(_,i)=>i+1);
        const mins  = Array.from({length:60},(_,i)=>i);

        const hourItems = hours.map(h=>`
            <div class="dtp-drum-item ${h===s.hour?'active':''}" onclick="DTP.setHour('${id}',${h})" data-val="${h}">
                ${String(h).padStart(2,'0')}
            </div>`).join('');

        const minItems = mins.map(m=>`
            <div class="dtp-drum-item ${m===s.min?'active':''}" onclick="DTP.setMin('${id}',${m})" data-val="${m}">
                ${String(m).padStart(2,'0')}
            </div>`).join('');

        const ampmSection = !is24 ? `
            <div class="flex flex-col gap-1 justify-center">
                <button onclick="DTP.setAmpm('${id}','AM')"
                        class="dtp-tab ${s.ampm==='AM'?'active':''} text-center">AM</button>
                <button onclick="DTP.setAmpm('${id}','PM')"
                        class="dtp-tab ${s.ampm==='PM'?'active':''} text-center">PM</button>
            </div>` : '';

        // Format toggle
        const fmtToggle = `
            <div class="flex items-center gap-2 text-xs text-slate-500">
                <span class="font-semibold">Format:</span>
                <button onclick="DTP.setFmt('${id}','12')"
                        class="dtp-tab ${s.fmt==='12'?'active':''} py-0.5 px-2">12h</button>
                <button onclick="DTP.setFmt('${id}','24')"
                        class="dtp-tab ${s.fmt==='24'?'active':''} py-0.5 px-2">24h</button>
            </div>`;

        return `
        <div class="p-4">
            <!-- Format toggle -->
            <div class="flex justify-between items-center mb-4">
                <p class="text-sm font-bold text-slate-700">Select Time</p>
                ${fmtToggle}
            </div>

            <!-- Time display preview -->
            <div class="text-center mb-4">
                <span class="text-3xl font-bold text-slate-800 tracking-tight" id="dtp_preview_${id}">
                    ${fmtTime(s)}
                </span>
            </div>

            <!-- Drums -->
            <div class="flex items-center justify-center gap-3">
                <!-- Hours -->
                <div class="text-center">
                    <p class="text-xs text-slate-400 font-semibold mb-1">Hour</p>
                    <!-- Up arrow -->
                    <button onclick="DTP.nudgeHour('${id}',-1,'${is24?'24':'12'}')"
                            class="w-full flex justify-center py-1 hover:bg-slate-100 rounded transition text-slate-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg>
                    </button>
                    <div class="relative">
                        <div id="dtp_hdrum_${id}" class="dtp-drum w-14 bg-slate-50 rounded-lg border border-slate-200">${hourItems}</div>
                        <!-- Highlight bar -->
                        <div class="pointer-events-none absolute left-0 right-0 top-1/2 -translate-y-1/2 h-9 border-y-2 border-blue-400 rounded-sm opacity-30"></div>
                    </div>
                    <button onclick="DTP.nudgeHour('${id}',1,'${is24?'24':'12'}')"
                            class="w-full flex justify-center py-1 hover:bg-slate-100 rounded transition text-slate-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                </div>

                <span class="text-2xl font-bold text-slate-400 mt-3">:</span>

                <!-- Minutes -->
                <div class="text-center">
                    <p class="text-xs text-slate-400 font-semibold mb-1">Min</p>
                    <button onclick="DTP.nudgeMin('${id}',-1)"
                            class="w-full flex justify-center py-1 hover:bg-slate-100 rounded transition text-slate-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg>
                    </button>
                    <div class="relative">
                        <div id="dtp_mdrum_${id}" class="dtp-drum w-14 bg-slate-50 rounded-lg border border-slate-200">${minItems}</div>
                        <div class="pointer-events-none absolute left-0 right-0 top-1/2 -translate-y-1/2 h-9 border-y-2 border-blue-400 rounded-sm opacity-30"></div>
                    </div>
                    <button onclick="DTP.nudgeMin('${id}',1)"
                            class="w-full flex justify-center py-1 hover:bg-slate-100 rounded transition text-slate-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                </div>

                <!-- AM/PM -->
                ${ampmSection}
            </div>

            <!-- Quick select -->
            <div class="mt-4 flex flex-wrap gap-1.5">
                ${['08:00','09:00','10:00','12:00','14:00','18:00','20:00','22:00'].map(t=>{
                    const [h,m]=t.split(':').map(Number);
                    return `<button onclick="DTP.quickTime('${id}',${h},${m})"
                                    class="text-xs px-2.5 py-1 rounded-full border border-slate-200 hover:border-blue-400 hover:text-blue-600 transition text-slate-600 font-medium">${t}</button>`;
                }).join('')}
            </div>

            <!-- Footer -->
            <div class="flex justify-between items-center mt-4 pt-3 border-t border-slate-100">
                <button onclick="DTP.clear('${id}')" class="btn-outline text-xs py-1.5 px-3">Clear</button>
                <div class="flex gap-2">
                    ${DTP_STATE[id].mode==='datetime' ? `<button onclick="DTP.setTab('${id}','date')" class="btn-outline text-xs py-1.5 px-3">← Back</button>` : ''}
                    <button onclick="DTP.confirm('${id}')" class="btn-primary text-xs py-1.5 px-3">Done</button>
                </div>
            </div>
        </div>`;
    },

    /* ── Navigation ── */
    prevMonth(id) {
        const s=DTP_STATE[id];
        if(s.navMonth===0){s.navMonth=11;s.navYear--;}else s.navMonth--;
        DTP.render(id);
    },
    nextMonth(id) {
        const s=DTP_STATE[id];
        if(s.navMonth===11){s.navMonth=0;s.navYear++;}else s.navMonth++;
        DTP.render(id);
    },
    setMonth(id,m){ DTP_STATE[id].navMonth=parseInt(m); DTP.render(id); },
    setYear(id,y) { DTP_STATE[id].navYear=parseInt(y);  DTP.render(id); },

    /* ── Day selection ── */
    selectDay(id, y, m, d) {
        const s  = DTP_STATE[id];
        const dt = new Date(y,m,d);

        if (s.mode === 'range') {
            if (!s.date || s.rangeSelecting===false) {
                s.date = dt; s.endDate = null; s.rangeSelecting = true;
            } else {
                if (dt < s.date) { s.endDate=s.date; s.date=dt; }
                else              { s.endDate = dt; }
                s.rangeSelecting = false;
            }
        } else {
            s.date = dt;
        }
        DTP.render(id);
    },

    /* ── Time ── */
    setHour(id,h)  { DTP_STATE[id].hour=h; DTP.refreshTime(id); },
    setMin(id,m)   { DTP_STATE[id].min=m;  DTP.refreshTime(id); },
    setAmpm(id,ap) { DTP_STATE[id].ampm=ap; DTP.render(id); },
    setFmt(id,f)   {
        const s=DTP_STATE[id]; s.fmt=f;
        s.hour = f==='24' ? (s.ampm==='PM'&&s.hour<12?s.hour+12:s.ampm==='AM'&&s.hour===12?0:s.hour)
                          : (s.hour===0?12:s.hour>12?s.hour-12:s.hour);
        DTP.render(id);
    },
    nudgeHour(id, delta, type) {
        const s=DTP_STATE[id];
        const max=type==='24'?23:12; const min=type==='24'?0:1;
        s.hour=s.hour+delta; if(s.hour>max)s.hour=min; if(s.hour<min)s.hour=max;
        DTP.render(id);
    },
    nudgeMin(id, delta) {
        const s=DTP_STATE[id];
        s.min=s.min+delta; if(s.min>59)s.min=0; if(s.min<0)s.min=59;
        DTP.render(id);
    },
    quickTime(id,h,m) {
        const s=DTP_STATE[id]; s.min=m;
        if(s.fmt==='12'){
            s.ampm=h>=12?'PM':'AM'; s.hour=h>12?h-12:h===0?12:h;
        } else { s.hour=h; }
        DTP.render(id);
    },
    refreshTime(id) {
        // lightweight — just update active states & preview without full re-render
        DTP.render(id);
    },

    /* ── Sync drum scroll position ── */
    syncDrums(id) {
        const s=DTP_STATE[id];
        setTimeout(()=>{
            const hd=document.getElementById(`dtp_hdrum_${id}`);
            const md=document.getElementById(`dtp_mdrum_${id}`);
            if(hd){ const item=hd.querySelector(`.dtp-drum-item[data-val="${s.hour}"]`); item?.scrollIntoView({block:'center',behavior:'smooth'}); }
            if(md){ const item=md.querySelector(`.dtp-drum-item[data-val="${s.min}"]`);  item?.scrollIntoView({block:'center',behavior:'smooth'}); }
        },50);
    },

    /* ── Clear & Confirm ── */
    clear(id) {
        const s=DTP_STATE[id]; s.date=null; s.endDate=null; s.rangeSelecting=false;
        s.hour=s.fmt==='12'?12:0; s.min=0; s.ampm='AM';
        const el=document.getElementById(`dtp_lbl_${id}`);
        el.textContent=el.getAttribute('data-ph')||'Select Date';
        el.style.color='#94a3b8'; el.style.fontWeight='400';
        DTP.render(id);
    },

    confirm(id) {
        const s=DTP_STATE[id];
        // Build display string
        let display='';
        if(s.mode==='time'){
            display=fmtTime(s)||'Select Time';
        } else if(s.mode==='range'){
            display=s.date&&s.endDate?`${fmtDate(s.date)} → ${fmtDate(s.endDate)}`:'Select Dates';
        } else if(s.mode==='datetime'){
            display=s.date?`${fmtDate(s.date)}, ${fmtTime(s)}`:'Select Date & Time';
        } else {
            display=s.date?fmtDate(s.date):'Select Date';
        }

        const lbl=document.getElementById(`dtp_lbl_${id}`);
        lbl.textContent=display;
        // Same text style as travelers label
        lbl.style.color='#1e293b';
        lbl.style.fontWeight='500';
        DTP.close(id);
    },

    /* ── Get value (JS se call karo) ── */
    getValue(id) {
        const s=DTP_STATE[id]; if(!s) return null;
        return {
            date    : s.date,
            endDate : s.endDate,
            hour    : s.hour,
            min     : s.min,
            ampm    : s.ampm,
            fmt     : s.fmt,
            timeStr : fmtTime(s),
            dateStr : fmtDate(s.date),
        };
    }
};

/* ── Auto-init all [data-dtp] elements ── */
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-dtp]').forEach(el => DTP.init(el));
});

/* ── Outside click close ──
   FIX: render() ke baad purana DOM replace hota hai,
   e.target DOM mein nahi rehta — isConnected check karo ── */
document.addEventListener('click', e => {
    if (!DTP_OPEN.id) return;
    if (!e.target.isConnected) return;  // re-render ke baad target DOM se hat jaata hai
    if (e.target.closest('[data-dtp]')) return;  // andar click tha
    DTP.close(DTP_OPEN.id);
});

/* ── Demo: read values ── */
function showValues() {
    const ids=['fl_depart','fl_datetime','fl_return','pickup_t','ht_range','ht_checkin','ht_checkout','ht_special'];
    const result={};
    ids.forEach(id=>{ result[id]=DTP.getValue(id); });
    const out=document.getElementById('output');
    out.classList.remove('hidden');
    out.textContent=JSON.stringify(result,null,2);
}
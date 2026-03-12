document.addEventListener("DOMContentLoaded", function () {
    const loader = document.getElementById("Loader");
    if (loader) loader.style.display = "flex";
    setTimeout(() => {
        if (loader) loader.style.display = "none";
    }, 1000);
});

document.addEventListener("DOMContentLoaded", () => {
    const icons = document.querySelectorAll("i[data-tabler]");
    icons.forEach(async (icon) => {
        const iconName = icon.getAttribute("data-tabler").toLowerCase();
        const url = `https://cdn.jsdelivr.net/npm/@tabler/icons@latest/icons/${iconName}.svg`;
        try {
            const response = await fetch(url);
            if (!response.ok) {
                console.warn(`Icon "${iconName}" load nahi ho paya.`);
                return;
            }
            const svgText = await response.text();
            icon.innerHTML = svgText;
            const svg = icon.querySelector("svg");
            if (svg) {
                const size = icon.getAttribute("data-size") || "24";
                const stroke = icon.getAttribute("data-stroke") || "1.5";
                svg.setAttribute("width", size);
                svg.setAttribute("height", size);
                svg.setAttribute("stroke-width", stroke);
                svg.style.display = "inline-block";
                svg.style.verticalAlign = "middle";
                svg.style.stroke = "currentColor";
            }
        } catch (err) {
            console.error("Icon fetching error:", err);
        }
    });

    const mainTabs = document.querySelectorAll(".tabs-border");
    const mainPanels = document.querySelectorAll(".search-tab-content > div");
    if (mainTabs.length > 0 && mainPanels.length > 0) {
        mainTabs.forEach((tab, index) => {
            tab.addEventListener("click", () => {
                mainTabs.forEach((t) => t.classList.remove("active"));
                tab.classList.add("active");
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
                    subTabs.forEach((t) => t.classList.remove("active"));
                    tab.classList.add("active");
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

//══════════════════════════════════════════ Travels ══════════════════════════════════════════

const PAX = {};

function initPax(id, adults = 1, children = 0, infants = 0) {
    PAX[id] = {
        adults: adults,
        children: children,
        infants: infants,
    };
}

function toggleTravelers(id) {
    const dropdown = document.getElementById(id + "Dropdown");
    dropdown.classList.toggle("hidden");
}

function closeTravelers(id) {
    document.getElementById(id + "Dropdown").classList.add("hidden");
}

function ensurePax(id) {
    if (!PAX[id]) {
        PAX[id] = {
            adults: 1,
            children: 0,
            infants: 0,
        };
    }
}

function changePax(id, type, delta) {
    ensurePax(id);
    const limits = {
        adults: [1, 9],
        children: [0, 9],
        infants: [0, 9],
    };
    const [min, max] = limits[type];
    PAX[id][type] = Math.min(max, Math.max(min, PAX[id][type] + delta));
    document.getElementById(`${id}_${type}-count`).textContent = PAX[id][type];
    updateTravelersLabel(id);
}

function updateTravelersLabel(id) {
    ensurePax(id);
    const wrapper = document.getElementById(id + "Wrapper");
    const cls =
        wrapper.querySelector(`input[name='${id}_cabinClass']:checked`)
            ?.value || "Economy";

    const total = PAX[id].adults + PAX[id].children + PAX[id].infants;

    const word = total === 1 ? "Traveler" : "Travelers";
    document.getElementById(id + "Label").textContent =
        `${total} ${word}, ${cls}`;
    document.getElementById(id + "_inp_adults").value = PAX[id].adults;
    document.getElementById(id + "_inp_children").value = PAX[id].children;
    document.getElementById(id + "_inp_infants").value = PAX[id].infants;
    document.getElementById(id + "_inp_class").value = cls;
}

document.addEventListener("click", function (e) {
    document.querySelectorAll("[id$='Wrapper']").forEach((wrapper) => {
        if (!e.target.closest("#" + wrapper.id)) {
            const dropdown = wrapper.querySelector("[id$='Dropdown']");
            dropdown?.classList.add("hidden");
        }
    });
});
/* ══════════════════════════════════════════ Airports ══════════════════════════════════════════ */

const AIRPORTS = [
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
        name: "Kempegowda International Airport",
        country: "India",
    },
    {
        code: "BOM",
        city: "Mumbai",
        name: "Chhatrapati Shivaji Maharaj Intl Airport",
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
        code: "MAA",
        city: "Chennai",
        name: "Chennai International Airport",
        country: "India",
    },
    {
        code: "AMD",
        city: "Ahmedabad",
        name: "Sardar Vallabhbhai Patel Intl Airport",
        country: "India",
    },
    {
        code: "COK",
        city: "Kochi",
        name: "Cochin International Airport",
        country: "India",
    },
    {
        code: "IXC",
        city: "Chandigarh",
        name: "Chandigarh International Airport",
        country: "India",
    },
    {
        code: "DXB",
        city: "Dubai",
        name: "Dubai International Airport",
        country: "UAE",
    },
    {
        code: "AUH",
        city: "Abu Dhabi",
        name: "Zayed International Airport",
        country: "UAE",
    },
    { code: "LHR", city: "London", name: "Heathrow Airport", country: "UK" },
    {
        code: "SIN",
        city: "Singapore",
        name: "Changi Airport",
        country: "Singapore",
    },
    {
        code: "BKK",
        city: "Bangkok",
        name: "Suvarnabhumi Airport",
        country: "Thailand",
    },
    {
        code: "CDG",
        city: "Paris",
        name: "Charles de Gaulle Airport",
        country: "France",
    },
    {
        code: "JFK",
        city: "New York",
        name: "John F. Kennedy International Airport",
        country: "USA",
    },
    {
        code: "NRT",
        city: "Tokyo",
        name: "Narita International Airport",
        country: "Japan",
    },
];
const TOP = ["DEL", "BLR", "BOM", "CCU", "JAI", "HYD", "MAA", "DXB"];

async function apiAirports(q) {
    try {
        const r = await fetch(
            `airport-search?keyword=${encodeURIComponent(q)}`,
            {
                headers: {
                    Accept: "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                },
            },
        );

        if (!r.ok) throw 0;

        const j = await r.json();
        const list = j.data ?? [];

        return list.map((a) => ({
            code: a.iata_code ?? "",
            city: a.city_name
                ? a.city_name.charAt(0) +
                  a.city_name.slice(1).toLowerCase()
                : "",
            name: a.name ?? "",
            country: a.iata_country_code ?? "",
        }));
    } catch {
        return null;
    }
}

function localSearch(q) {
    if (!q) return AIRPORTS;
    const lq = q.toLowerCase();
    return AIRPORTS.filter(
        (a) =>
            a.city.toLowerCase().includes(lq) ||
            a.code.toLowerCase().includes(lq) ||
            a.name.toLowerCase().includes(lq),
    );
}

const timers = {};
function debounce(fn, key, ms = 300) {
    clearTimeout(timers[key]);
    timers[key] = setTimeout(fn, ms);
}

const PLANE = `<svg viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>`;

/* ══════════════════════════════════════
   RENDER RESULTS
══════════════════════════════════════ */
function renderResults(resultsEl, data, query) {
    if (!data.length) {
        resultsEl.innerHTML = `<div class="ap-empty">No airports found</div>`;
        return;
    }

    let html = "";
    if (!query) {
        const top = data.filter((a) => TOP.includes(a.code));
        const rest = data.filter((a) => !TOP.includes(a.code));
        if (top.length) html += groupHTML("Top Cities", top);
        if (rest.length) html += groupHTML("Other Airports", rest);
    } else {
        html = data.map(optionHTML).join("");
    }

    resultsEl.innerHTML = html;
}

function groupHTML(label, list) {
    return (
        `<div class="ap-group-label">${label}</div>` +
        list.map(optionHTML).join("")
    );
}

function optionHTML(a) {
    return `
    <div class="ap-option" data-code="${a.code}" data-city="${a.city}" data-name="${a.name}" data-country="${a.country}">
      <div class="ap-opt-icon">${PLANE}</div>
      <div class="ap-opt-body">
        <div class="ap-opt-title">${a.city} <span class="ap-opt-code">(${a.code})</span></div>
        <div class="ap-opt-sub">${a.name}</div>
      </div>
      <div class="ap-opt-cntry">${a.country}</div>
    </div>`;
}

/* ══════════════════════════════════════
   INIT EACH FIELD
══════════════════════════════════════ */
function initField(fieldEl) {
    const id = fieldEl.dataset.id;
    const dropdown = fieldEl.querySelector(".ap-dropdown");
    const searchInp = fieldEl.querySelector(".ap-search-input");
    const resultsEl = fieldEl.querySelector(".ap-results");
    const display = fieldEl.querySelector(".ap-display");
    const hidden = fieldEl.querySelector(".ap-hidden");
    const cityHidden = fieldEl.querySelector(".ap-city-hidden");

    function openDropdown() {
        document.querySelectorAll(".ap-dropdown.open").forEach((d) => {
            if (d !== dropdown) d.classList.remove("open");
        });
        dropdown.classList.add("open");
        searchInp.value = "";
        searchInp.focus();
        renderResults(resultsEl, AIRPORTS, "");
        bindOptionClicks();
    }

    function closeDropdown() {
        dropdown.classList.remove("open");
        searchInp.value = "";
    }

    async function loadResults(q) {
        resultsEl.innerHTML = `<div class="ap-empty" style="padding:16px"><span class="loading loading-dots loading-lg"></span></div>`;
        let data = await apiAirports(q);
        if (!data) data = localSearch(q);
        renderResults(resultsEl, data, q);
        bindOptionClicks();
    }

    function bindOptionClicks() {
        resultsEl.querySelectorAll(".ap-option").forEach((opt) => {
            opt.addEventListener("click", () => {
                const code = opt.dataset.code;
                const city = opt.dataset.city;
                display.textContent = `${code} – ${city}`;
                display.classList.remove("text-slate-400");
                display.classList.add("text-slate-800");
                hidden.value = code;
                if (cityHidden) {
                    cityHidden.value = city;
                }
                closeDropdown();
            });
        });
    }

    fieldEl.addEventListener("click", (e) => {
        if (dropdown.contains(e.target)) return;
        openDropdown();
    });

    searchInp.addEventListener("input", () => {
        const q = searchInp.value.trim();
        debounce(() => loadResults(q), id);
    });

    dropdown.addEventListener("click", (e) => e.stopPropagation());
    const defCode = fieldEl.dataset.default;
    if (defCode) {
        const ap = AIRPORTS.find((a) => a.code === defCode);
        if (ap) {
            display.textContent = `${ap.code} – ${ap.city}`;
            hidden.value = ap.code;
        }
    }
}

/* ══════════════════════════════════════ CLOSE ON OUTSIDE CLICK ══════════════════════════════════════ */

document.addEventListener("click", (e) => {
    if (!e.target.closest(".ap-field")) {
        document
            .querySelectorAll(".ap-dropdown.open")
            .forEach((d) => d.classList.remove("open"));
    }
});
document.querySelectorAll(".ap-field").forEach(initField);

//══════════════════════════════════════  Date Time Picker ══════════════════════════════════════

const MONTHS = [
    "January",
    "February",
    "March",
    "April",
    "May",
    "June",
    "July",
    "August",
    "September",
    "October",
    "November",
    "December",
];
const WDAYS = ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"];

const _dtp = {};
let _dtpOpen = null;

function dtpParseMin(val) {
    if (!val) return null;
    if (val === "today") {
        const t = new Date();
        t.setHours(0, 0, 0, 0);
        return t;
    }
    const d = new Date(val);
    d.setHours(0, 0, 0, 0);
    return d;
}

function dtpDateOnly(d) {
    const x = new Date(d);
    x.setHours(0, 0, 0, 0);
    return x;
}

function dtpFmt(d) {
    if (!d) return "";
    return `${d.getDate()} ${MONTHS[d.getMonth()].slice(0, 3)} ${d.getFullYear()}`;
}

function dtpLocalISO(d) {
    if (!d) return "";
    const y = d.getFullYear();
    const m = String(d.getMonth() + 1).padStart(2, "0");
    const dd = String(d.getDate()).padStart(2, "0");
    return `${y}-${m}-${dd}`;
}

function dtpInit(fieldEl) {
    const id = fieldEl.dataset.dtpId;
    const mode = fieldEl.dataset.mode || "date";
    const minD = dtpParseMin(fieldEl.dataset.minDate);
    const maxD = dtpParseMin(fieldEl.dataset.maxDate);

    _dtp[id] = {
        mode,
        minD,
        maxD,
        navYear: new Date().getFullYear(),
        navMonth: new Date().getMonth(),
        date: null,
        endDate: null,
        selecting: false,
    };

    const hiddenInp = document.getElementById(`dtp_val_${id}`);

    if (hiddenInp && hiddenInp.value) {
        const existing = new Date(hiddenInp.value);
        existing.setHours(0, 0, 0, 0);
        _dtp[id].date = existing;
        _dtp[id].navYear = existing.getFullYear();
        _dtp[id].navMonth = existing.getMonth();
    } else if (hiddenInp && hiddenInp.hasAttribute("data-default-today")) {
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        _dtp[id].date = today;
    }

    fieldEl.addEventListener("click", () => {
        if (_dtpOpen && _dtpOpen !== id) dtpClose(_dtpOpen);
        _dtpOpen === id ? dtpClose(id) : dtpOpen(id);
    });

    dtpRender(id);
    if (_dtp[id].date) {
        const lbl = document.getElementById(`dtp_lbl_${id}`);
        const val = document.getElementById(`dtp_val_${id}`);
        if (lbl) {
            lbl.textContent = dtpFmt(_dtp[id].date);
            lbl.style.color = "#1e293b";
            lbl.style.fontWeight = "500";
        }
        if (val) val.value = dtpLocalISO(_dtp[id].date);
    }
}

function dtpOpen(id) {
    _dtpOpen = id;
    document.getElementById(`dtp_dd_${id}`).classList.add("open");
    document
        .querySelectorAll(".ap-dropdown.open")
        .forEach((d) => d.classList.remove("open"));
    dtpRender(id);
}

function dtpClose(id) {
    document.getElementById(`dtp_dd_${id}`)?.classList.remove("open");
    if (_dtpOpen === id) _dtpOpen = null;
}

function dtpRender(id) {
    const s = _dtp[id];
    const body = document.getElementById(`dtp_body_${id}`);
    const today = dtpDateOnly(new Date());
    const year = s.navYear;
    const month = s.navMonth;
    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const thisYear = new Date().getFullYear();

    const yearOpts = Array.from({ length: 7 }, (_, i) => thisYear - 1 + i)
        .map(
            (y) =>
                `<option value="${y}"${y === year ? " selected" : ""}>${y}</option>`,
        )
        .join("");

    const monthOpts = MONTHS.map(
        (m, i) =>
            `<option value="${i}"${i === month ? " selected" : ""}>${m}</option>`,
    ).join("");

    let cells = "";
    for (let i = 0; i < firstDay; i++) cells += `<div></div>`;
    for (let d = 1; d <= daysInMonth; d++) {
        const dt = dtpDateOnly(new Date(year, month, d));
        const dis = (s.minD && dt < s.minD) || (s.maxD && dt > s.maxD);
        const isTd = dt.getTime() === today.getTime();
        const isSl = s.date && dt.getTime() === dtpDateOnly(s.date).getTime();
        const isEn =
            s.endDate && dt.getTime() === dtpDateOnly(s.endDate).getTime();
        const inRg =
            s.mode === "range" &&
            s.date &&
            s.endDate &&
            dt > dtpDateOnly(s.date) &&
            dt < dtpDateOnly(s.endDate);

        let cls = "dtp-day";
        if (dis) cls += " disabled";
        if (isTd) cls += " today";
        if (isSl)
            cls +=
                " selected" +
                (s.mode === "range" && s.endDate ? " range-start" : "");
        if (isEn) cls += " selected range-end";
        if (inRg) cls += " in-range";
        cells += `<div class="${cls}" data-y="${year}" data-m="${month}" data-d="${d}">${d}</div>`;
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
        ${WDAYS.map((d) => `<div class="dtp-day text-xs font-bold text-slate-400 cursor-default">${d}</div>`).join("")}
      </div>
      <div class="grid grid-cols-7 gap-y-0.5">${cells}</div>
      ${s.mode === "range" ? `<p class="mt-2 text-xs text-center text-slate-400">${!s.date ? "Select check-in" : !s.endDate ? "Select check-out" : `${dtpFmt(s.date)} → ${dtpFmt(s.endDate)}`}</p>` : ""}
      <div class="flex justify-between items-center mt-3 pt-3 border-t border-slate-100">
        <button type="button" class="dtp-clear btn-outline text-xs py-1.5 px-3">Clear</button>
        <button type="button" class="dtp-done btn-primary text-xs py-1.5 px-3">Done</button>
      </div>
    </div>`;

    body.querySelector(".dtp-prev").onclick = () => {
        s.navMonth === 0 ? ((s.navMonth = 11), s.navYear--) : s.navMonth--;
        dtpRender(id);
    };
    body.querySelector(".dtp-next").onclick = () => {
        s.navMonth === 11 ? ((s.navMonth = 0), s.navYear++) : s.navMonth++;
        dtpRender(id);
    };
    body.querySelector(".dtp-month").onchange = (e) => {
        s.navMonth = +e.target.value;
        dtpRender(id);
    };
    body.querySelector(".dtp-year").onchange = (e) => {
        s.navYear = +e.target.value;
        dtpRender(id);
    };

    body.querySelector(".dtp-clear").onclick = () => {
        s.date = null;
        s.endDate = null;
        s.selecting = false;
        const lbl = document.getElementById(`dtp_lbl_${id}`);
        lbl.textContent = "Select date";
        lbl.style.color = "#94a3b8";
        lbl.style.fontWeight = "400";
        document.getElementById(`dtp_val_${id}`).value = "";
        dtpRender(id);
    };

    body.querySelector(".dtp-done").onclick = () => {
        const lbl = document.getElementById(`dtp_lbl_${id}`);
        const val = document.getElementById(`dtp_val_${id}`);
        let display = "";
        if (s.mode === "range")
            display =
                s.date && s.endDate
                    ? `${dtpFmt(s.date)} → ${dtpFmt(s.endDate)}`
                    : "Select dates";
        else display = s.date ? dtpFmt(s.date) : "Select date";
        lbl.textContent = display;
        lbl.style.color = s.date ? "#1e293b" : "#94a3b8";
        lbl.style.fontWeight = s.date ? "500" : "400";
        val.value = dtpLocalISO(s.date);
        const endVal = document.getElementById(`dtp_end_${id}`);
        if (endVal && s.endDate) {
            endVal.value = dtpLocalISO(s.endDate);
        }
        dtpClose(id);
    };

    body.querySelectorAll(".dtp-day[data-d]").forEach((cell) => {
        cell.onclick = () => {
            const dt = new Date(
                +cell.dataset.y,
                +cell.dataset.m,
                +cell.dataset.d,
            );
            if (s.mode === "range") {
                if (!s.date || !s.selecting) {
                    s.date = dt;
                    s.endDate = null;
                    s.selecting = true;
                } else {
                    if (dt < s.date) {
                        s.endDate = s.date;
                        s.date = dt;
                    } else {
                        s.endDate = dt;
                    }
                    s.selecting = false;
                }
            } else {
                s.date = dt;
            }
            dtpRender(id);
        };
    });
}

document.querySelectorAll(".dtp-field").forEach(dtpInit);

document.addEventListener("click", (e) => {
    if (!e.target.isConnected) return;
    if (!e.target.closest(".ap-field"))
        document
            .querySelectorAll(".ap-dropdown.open")
            .forEach((d) => d.classList.remove("open"));
    if (!e.target.closest(".dtp-field") && _dtpOpen) dtpClose(_dtpOpen);
});

//=================== Change Trip Type Handle ====================

document.querySelectorAll(".trip-tab").forEach((tab) => {
    tab.addEventListener("click", function () {
        document
            .querySelectorAll(".trip-tab")
            .forEach((t) => t.classList.remove("active"));
        this.classList.add("active");
        document.getElementById("trip_type").value = this.dataset.trip;
    });
});

//===================== Hotel ===================

const HG = { rooms: 1, adults: 1, children: 0, pets: 0 };
const HG_LIMITS = { rooms: [1, 9], adults: [1, 30], children: [0, 10] };

function toggleHG() {
    const dd = document.getElementById("hgDropdown");
    dd.classList.toggle("hidden");
    // close other dropdowns (dtp, ap) if open
    if (!dd.classList.contains("hidden")) {
        document
            .querySelectorAll(".ap-dropdown.open")
            .forEach((d) => d.classList.remove("open"));
        if (typeof _dtpOpen !== "undefined" && _dtpOpen) dtpClose(_dtpOpen);
        if (typeof closeTravelers === "function") closeTravelers();
    }
}
function closeHG() {
    document.getElementById("hgDropdown")?.classList.add("hidden");
}

function changeHG(type, delta) {
    const [mn, mx] = HG_LIMITS[type];
    HG[type] = Math.min(mx, Math.max(mn, HG[type] + delta));
    document.getElementById(`hg_disp_${type}`).textContent = HG[type];

    // minus button disable at min
    const minusBtn = document.getElementById(`btn_${type}_minus`);
    if (minusBtn) minusBtn.disabled = HG[type] <= mn;

    // children note
    document
        .getElementById("hgChildNote")
        ?.classList.toggle("hidden", HG.children === 0);

    updateHGLabel();
}

function handlePet() {
    const checked = document.getElementById("petCheck").checked;
    HG.pets = checked ? 1 : 0;
    document
        .getElementById("petCard")
        .classList.toggle("border-blue-400", checked);
    document
        .getElementById("petCard")
        .classList.toggle("bg-blue-50/50", checked);
    document.getElementById("petPaw").style.color = checked ? "#3b82f6" : "";
    updateHGLabel();
}

function updateHGLabel() {
    const adultWord = HG.adults === 1 ? "adult" : "adults";
    const childWord = HG.children === 1 ? "child" : "children";
    const roomWord = HG.rooms === 1 ? "room" : "rooms";
    const petStr = HG.pets ? " · pets" : "";

    // label — same format as original "1 adult · 0 children · 1 room"
    document.getElementById("hgLabel").textContent =
        `${HG.adults} ${adultWord} · ${HG.children} ${childWord} · ${HG.rooms} ${roomWord}${petStr}`;

    document.getElementById("hg_rooms").value = HG.rooms;
    document.getElementById("hg_adults").value = HG.adults;
    document.getElementById("hg_children").value = HG.children;
    document.getElementById("hg_pets").value = HG.pets;
}

function applyHG() {
    updateHGLabel();
    closeHG();
}

// outside click close
document.addEventListener("click", (e) => {
    if (!e.target.closest("#hgWrapper")) closeHG();
});

// init — minus buttons disabled at starting min
document.getElementById("btn_rooms_minus").disabled = true; // rooms start=1
document.getElementById("btn_adults_minus").disabled = true; // adults start=1
document.getElementById("btn_children_minus").disabled = true; // children start=0

//========================== Multi City ============================================

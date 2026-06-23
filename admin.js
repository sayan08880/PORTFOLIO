/* =========================================================
   PORTFOLIO ADMIN PANEL — FIREBASE SERVER-LEVEL VERSION
   - Password: Sayan@2005
   - Data saved to Firebase Firestore (global, not localStorage)
   - All portfolio pages auto-read from Firestore via portfolio-data.js
   - localStorage is kept as fallback/offline cache
========================================================= */

// ── Firebase SDK (modular via CDN compat build) ──────────
import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-app.js";
import { getFirestore, doc, setDoc, getDoc } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-firestore.js";

const firebaseConfig = {
  apiKey: "AIzaSyDbESNVQuA9hG1XSA_f2Q4L6ldCCUw121w",
  authDomain: "portfolio-28b65.firebaseapp.com",
  projectId: "portfolio-28b65",
  storageBucket: "portfolio-28b65.firebasestorage.app",
  messagingSenderId: "475965194628",
  appId: "1:475965194628:web:fcd85ee57b354013f21dd1",
  measurementId: "G-FD3L5LGNXQ"
};

const fbApp = initializeApp(firebaseConfig);
const db    = getFirestore(fbApp);
const DATA_DOC = doc(db, "portfolio", "cms");

// ── Helpers ───────────────────────────────────────────────
async function fbSave(data) {
  await setDoc(DATA_DOC, { payload: JSON.stringify(data) });
}

async function fbLoad() {
  const snap = await getDoc(DATA_DOC);
  if (snap.exists()) {
    try { return JSON.parse(snap.data().payload); } catch(e) { return null; }
  }
  return null;
}

// ── Main IIFE (runs after Firebase initialised) ───────────
(async function () {
  const CMS = window.PortfolioCMS;

  if (!CMS) {
    document.addEventListener("DOMContentLoaded", () => {
      document.body.innerHTML = `
        <div style="min-height:100vh;display:grid;place-items:center;background:#050433;color:white;font-family:Arial;padding:20px;text-align:center;">
          <div>
            <h2>PortfolioCMS not loaded</h2>
            <p>Make sure portfolio-data.js is loaded before admin.js</p>
          </div>
        </div>`;
    });
    return;
  }

  const ADMIN_PASSWORD = "Sayan@2005";

  const E = CMS.escapeHtml   || ((v) => String(v ?? ""));
  const P = CMS.clampPercent || ((v) => Math.min(100, Math.max(0, Math.round(Number(v) || 0))));
  const $ = (id) => document.getElementById(id);

  let data = clone(CMS.DEFAULT_DATA);

  // ── Data utilities ─────────────────────────────────────
  function clone(v) { return JSON.parse(JSON.stringify(v)); }
  function isObj(v) { return v && typeof v === "object" && !Array.isArray(v); }

  function mergeDeep(base, over) {
    const out = clone(base);
    if (!isObj(over)) return out;
    Object.keys(over).forEach((k) => {
      if (Array.isArray(over[k]))        out[k] = clone(over[k]);
      else if (isObj(over[k]) && isObj(out[k])) out[k] = mergeDeep(out[k], over[k]);
      else                               out[k] = over[k];
    });
    return out;
  }

  function ensureObj(parent, key, fb = {}) {
    if (!isObj(parent[key])) parent[key] = clone(fb);
  }
  function ensureArray(parent, key, fb = []) {
    if (!Array.isArray(parent[key])) parent[key] = clone(fb);
  }

  function normData(raw) {
    const base = clone(CMS.DEFAULT_DATA || {});
    const safe = isObj(raw) ? mergeDeep(base, raw) : base;
    ensureObj(safe,    "site",  base.site  || {});
    ensureObj(safe,    "home",  base.home  || {});
    ensureObj(safe,    "about", base.about || {});
    ensureObj(safe,    "cv",    base.cv    || {});
    ensureArray(safe,  "projects", base.projects || []);
    ensureArray(safe,  "skills",   base.skills   || []);
    ensureArray(safe.cv, "skills",       (base.cv || {}).skills       || []);
    ensureArray(safe.cv, "projectGroups",(base.cv || {}).projectGroups|| []);
    ensureArray(safe.cv, "education",    (base.cv || {}).education    || []);
    ensureArray(safe.cv, "languages",    (base.cv || {}).languages    || []);
    return safe;
  }

  // ── Form helpers ────────────────────────────────────────
  function val(id, fb = "") { const el=$(id); return el ? el.value.trim() : fb; }
  function setVal(id, v)    { const el=$(id); if (el) el.value = v ?? ""; }

  let statusTimer;
  function status(msg, isErr = false) {
    const el = $("status") || $("loginStatus");
    if (!el) return;
    el.textContent = msg;
    el.style.color = isErr ? "#fecaca" : "#86efac";
    clearTimeout(statusTimer);
    statusTimer = setTimeout(() => (el.textContent = ""), 5000);
  }

  function setBusy(on) {
    const btn = $("saveBtn");
    if (!btn) return;
    btn.disabled  = on;
    btn.textContent = on ? "Saving to Firebase…" : "Save Changes";
  }

  // ── Render helpers ──────────────────────────────────────
  function rowNumber(i) { return String(i + 1).padStart(2, "0"); }

  function textInput(label, field, value, type = "text") {
    return `<div class="field"><label>${E(label)}</label><input type="${E(type)}" data-field="${E(field)}" value="${E(value)}"></div>`;
  }
  function textareaInput(label, field, value) {
    return `<div class="field full"><label>${E(label)}</label><textarea data-field="${E(field)}">${E(value)}</textarea></div>`;
  }

  function renderRows(containerId, items, fields, title, addClass = "") {
    const c = $(containerId); if (!c) return;
    const safe = Array.isArray(items) ? items : [];
    c.innerHTML = safe.map((item, i) => `
      <div class="cms-row ${E(addClass)}" data-index="${i}">
        <div class="row-title">
          <strong>${E(title)} ${rowNumber(i)}</strong>
          <button type="button" class="btn btn-danger btn-small" data-delete="${E(containerId)}" data-index="${i}">Delete</button>
        </div>
        <div class="form-grid">
          ${fields.map((f) => f.textarea
            ? textareaInput(f.label, f.name, item?.[f.name] ?? "")
            : textInput(f.label, f.name, item?.[f.name] ?? "", f.type || "text")
          ).join("")}
        </div>
      </div>`).join("");
  }

  // ── Field definitions ───────────────────────────────────
  const projectFields = [
    { name:"title",       label:"Project Title" },
    { name:"image",       label:"Image Path"    },
    { name:"link",        label:"Project Link"  },
    { name:"linkLabel",   label:"Link Label"    },
    { name:"description", label:"Description",   textarea:true }
  ];
  const skillFields = [
    { name:"name",    label:"Skill Name"  },
    { name:"percent", label:"Percentage", type:"number" }
  ];
  const cvSkillFields = [
    { name:"icon",    label:"FontAwesome Icon Class" },
    { name:"label",   label:"Skill Label"            },
    { name:"title",   label:"Short Description"      },
    { name:"level",   label:"Level Tag"              },
    { name:"percent", label:"Percentage", type:"number" }
  ];
  const cvProjectFields = [
    { name:"icon",        label:"FontAwesome Icon Class" },
    { name:"label",       label:"Group Label"            },
    { name:"title",       label:"Title"                  },
    { name:"tag",         label:"Tag"                    },
    { name:"description", label:"Description", textarea:true }
  ];
  const educationFields = [
    { name:"icon",        label:"FontAwesome Icon Class" },
    { name:"label",       label:"Label"                  },
    { name:"title",       label:"Title"                  },
    { name:"tag",         label:"Year / Tag"             },
    { name:"description", label:"Description", textarea:true }
  ];
  const languageFields = [
    { name:"label",       label:"Language"              },
    { name:"title",       label:"Title"                 },
    { name:"tag",         label:"Level Tag"             },
    { name:"description", label:"Description", textarea:true }
  ];

  // ── Render form ─────────────────────────────────────────
  function renderForm() {
    setVal("site_logoText",    data.site.logoText);
    setVal("site_fullName",    data.site.fullName);
    setVal("site_profileImage",data.site.profileImage);
    setVal("site_github",      data.site.github);
    setVal("site_linkedin",    data.site.linkedin);
    setVal("site_facebook",    data.site.facebook);
    setVal("site_instagram",   data.site.instagram);

    setVal("home_greeting",    data.home.greeting);
    setVal("home_title",       data.home.title);
    setVal("home_tagline",     data.home.tagline);
    setVal("home_buttonText",  data.home.buttonText);
    setVal("home_buttonLink",  data.home.buttonLink);

    setVal("about_heading",    data.about.heading);
    setVal("about_subtitle",   data.about.subtitle);
    setVal("about_text",       data.about.text);

    setVal("cv_navInitials",   data.cv.navInitials);
    setVal("cv_badge",         data.cv.badge);
    setVal("cv_fullName",      data.cv.fullName);
    setVal("cv_subtitle",      data.cv.subtitle);
    setVal("cv_phone",         data.cv.phone);
    setVal("cv_email",         data.cv.email);
    setVal("cv_location",      data.cv.location);
    setVal("cv_profileHeading",data.cv.profileHeading);
    setVal("cv_profileText",   data.cv.profileText);
    setVal("cv_profileTag",    data.cv.profileTag);

    renderRows("projectRows",  data.projects,          projectFields,  "Project");
    renderRows("skillRows",    data.skills,             skillFields,    "Skill");
    renderRows("cvSkillRows",  data.cv.skills,          cvSkillFields,  "CV Skill");
    renderRows("cvProjectRows",data.cv.projectGroups,   cvProjectFields,"CV Project Group");
    renderRows("educationRows",data.cv.education,       educationFields,"Education");
    renderRows("languageRows", data.cv.languages,       languageFields, "Language");

    refreshJson();
  }

  // ── Collect form ────────────────────────────────────────
  function collectRows(containerId, numericFields = []) {
    const container = $(containerId); if (!container) return [];
    return Array.from(container.querySelectorAll(".cms-row")).map((row) => {
      const item = {};
      row.querySelectorAll("[data-field]").forEach((el) => {
        const k = el.dataset.field;
        item[k] = numericFields.includes(k) ? P(el.value) : el.value.trim();
      });
      return item;
    });
  }

  function collectForm() {
    data.site.logoText     = val("site_logoText");
    data.site.fullName     = val("site_fullName");
    data.site.profileImage = val("site_profileImage");
    data.site.github       = val("site_github");
    data.site.linkedin     = val("site_linkedin");
    data.site.facebook     = val("site_facebook");
    data.site.instagram    = val("site_instagram");

    data.home.greeting     = val("home_greeting");
    data.home.title        = val("home_title");
    data.home.tagline      = val("home_tagline");
    data.home.buttonText   = val("home_buttonText");
    data.home.buttonLink   = val("home_buttonLink");

    data.about.heading     = val("about_heading");
    data.about.subtitle    = val("about_subtitle");
    data.about.text        = val("about_text");

    data.cv.navInitials    = val("cv_navInitials");
    data.cv.badge          = val("cv_badge");
    data.cv.fullName       = val("cv_fullName");
    data.cv.subtitle       = val("cv_subtitle");
    data.cv.phone          = val("cv_phone");
    data.cv.email          = val("cv_email");
    data.cv.location       = val("cv_location");
    data.cv.profileHeading = val("cv_profileHeading");
    data.cv.profileText    = val("cv_profileText");
    data.cv.profileTag     = val("cv_profileTag");

    data.projects              = collectRows("projectRows");
    data.skills                = collectRows("skillRows",  ["percent"]);
    data.cv.skills             = collectRows("cvSkillRows",["percent"]);
    data.cv.projectGroups      = collectRows("cvProjectRows");
    data.cv.education          = collectRows("educationRows");
    data.cv.languages          = collectRows("languageRows");

    return data;
  }

  // ── Save to Firebase + localStorage ────────────────────
  async function save() {
    collectForm();
    setBusy(true);
    try {
      await fbSave(data);
      // Also update localStorage so pages read the same data offline
      if (CMS.saveData) CMS.saveData(data);
      refreshJson();
      status("✅ Saved to Firebase! Changes are now live for all visitors.");
    } catch(err) {
      console.error(err);
      // Fallback: still save locally
      if (CMS.saveData) CMS.saveData(data);
      status("⚠️ Firebase save failed — saved to browser only. Check Firestore rules.", true);
    } finally {
      setBusy(false);
    }
  }

  function refreshJson() {
    const j = $("jsonEditor"); if (j) j.value = JSON.stringify(data, null, 2);
  }

  function applyJson() {
    try {
      data = normData(JSON.parse(val("jsonEditor")));
      renderForm();
      status("JSON applied. Click Save Changes to push to Firebase.");
    } catch(err) {
      status("Invalid JSON. Fix the syntax first.", true);
    }
  }

  function exportJson() {
    collectForm();
    const blob = new Blob([JSON.stringify(data, null, 2)], { type:"application/json" });
    const url  = URL.createObjectURL(blob);
    const a    = document.createElement("a");
    a.href = url; a.download = "portfolio-data-export.json";
    document.body.appendChild(a); a.click(); a.remove();
    URL.revokeObjectURL(url);
    status("JSON exported.");
  }

  function importJson(file) {
    const reader = new FileReader();
    reader.onload = () => {
      try {
        data = normData(JSON.parse(reader.result));
        renderForm();
        status("Imported. Click Save Changes to push to Firebase.");
      } catch(err) {
        status("Import failed: invalid JSON file.", true);
      }
    };
    reader.readAsText(file);
  }

  function scrollToLastRow(cid) {
    const c = $(cid); if (!c) return;
    const last = c.querySelector(".cms-row:last-child");
    if (last) { last.scrollIntoView({behavior:"smooth",block:"center"}); last.classList.add("row-flash"); setTimeout(()=>last.classList.remove("row-flash"),1200); }
  }

  function addItem(target) {
    collectForm();
    const blanks = {
      project:    {title:"NEW PROJECT",description:"Write your project description here.",image:"logo.png",link:"#",linkLabel:"Open Project"},
      skill:      {name:"NEW SKILL",percent:50},
      cvSkill:    {icon:"fa-solid fa-code",label:"New Skill",title:"Skill description",level:"Basic",percent:50},
      cvProject:  {icon:"fa-solid fa-diagram-project",label:"Project",title:"Project title",description:"Project details",tag:"Tag"},
      education:  {icon:"fa-solid fa-graduation-cap",label:"Degree",title:"Course title",description:"Institute name",tag:"Year"},
      language:   {label:"Language",title:"Communication level",description:"Description",tag:"Level"}
    };
    const map = {
      project:   {arr:data.projects,         cid:"projectRows",   msg:"New project added."},
      skill:     {arr:data.skills,            cid:"skillRows",     msg:"New skill added."},
      cvSkill:   {arr:data.cv.skills,         cid:"cvSkillRows",   msg:"New CV skill added."},
      cvProject: {arr:data.cv.projectGroups,  cid:"cvProjectRows", msg:"New CV project group added."},
      education: {arr:data.cv.education,      cid:"educationRows", msg:"New education row added."},
      language:  {arr:data.cv.languages,      cid:"languageRows",  msg:"New language row added."}
    };
    const sel = map[target];
    if (!sel || !Array.isArray(sel.arr)) { status("Add failed — data is damaged. Click Reset Defaults.", true); return; }
    sel.arr.push(clone(blanks[target]));
    renderForm();
    status(sel.msg + " Edit it, then click Save Changes.");
    scrollToLastRow(sel.cid);
  }

  function deleteItem(containerId, index) {
    collectForm();
    const map = {
      projectRows:  data.projects,
      skillRows:    data.skills,
      cvSkillRows:  data.cv.skills,
      cvProjectRows:data.cv.projectGroups,
      educationRows:data.cv.education,
      languageRows: data.cv.languages
    };
    if (!Array.isArray(map[containerId])) { status("Delete failed — row data is damaged.", true); return; }
    map[containerId].splice(Number(index), 1);
    renderForm();
    status("Deleted. Click Save Changes to push to Firebase.");
  }

  async function resetDefaults() {
    if (!confirm("Reset ALL content to defaults and save to Firebase?")) return;
    data = clone(CMS.DEFAULT_DATA);
    setBusy(true);
    try {
      await fbSave(data);
      if (CMS.saveData) CMS.saveData(data);
      renderForm();
      status("✅ Defaults restored and saved to Firebase.");
    } catch(err) {
      if (CMS.saveData) CMS.saveData(data);
      status("Defaults restored locally only. Firebase save failed.", true);
    } finally { setBusy(false); }
  }

  // ── Login ───────────────────────────────────────────────
  function showApp() {
    $("loginView").hidden = true;
    $("adminApp").hidden  = false;
    makeParticles();
  }

  async function login() {
    const loginStatus = $("loginStatus");
    if (val("adminPassword") !== ADMIN_PASSWORD) {
      if (loginStatus) { loginStatus.textContent="Wrong password."; loginStatus.style.color="#fecaca"; }
      return;
    }
    sessionStorage.setItem("portfolio_admin_logged_in","yes");
    if (loginStatus) loginStatus.textContent = "";

    // Show loading indicator
    if (loginStatus) { loginStatus.textContent = "🔄 Loading data from Firebase…"; loginStatus.style.color="#93c5fd"; }

    try {
      const remote = await fbLoad();
      if (remote) {
        data = normData(remote);
        // Sync to localStorage so portfolio pages pick it up
        if (CMS.saveData) CMS.saveData(data);
      } else {
        // First time: push defaults to Firebase
        data = normData(CMS.getData ? CMS.getData() : CMS.DEFAULT_DATA);
        await fbSave(data);
        if (CMS.saveData) CMS.saveData(data);
      }
    } catch(err) {
      console.warn("Firebase load failed, falling back to localStorage:", err);
      data = normData(CMS.getData ? CMS.getData() : CMS.DEFAULT_DATA);
    }

    if (loginStatus) loginStatus.textContent = "";
    showApp();
    renderForm();
  }

  function checkSession() {
    if (sessionStorage.getItem("portfolio_admin_logged_in") === "yes") {
      // Re-fetch from Firebase on every page load
      (async () => {
        try {
          const remote = await fbLoad();
          if (remote) {
            data = normData(remote);
            if (CMS.saveData) CMS.saveData(data);
          } else {
            data = normData(CMS.getData ? CMS.getData() : CMS.DEFAULT_DATA);
          }
        } catch(e) {
          data = normData(CMS.getData ? CMS.getData() : CMS.DEFAULT_DATA);
        }
        showApp();
        renderForm();
      })();
    }
  }

  // ── Particles ───────────────────────────────────────────
  function makeParticles() {
    const c = $("particles"); if (!c || c.dataset.ready) return;
    c.dataset.ready = "true";
    for (let i = 0; i < 35; i++) {
      const d = document.createElement("div");
      d.classList.add("dot");
      d.style.left = Math.random()*100+"vw";
      d.style.top  = Math.random()*100+"vh";
      d.style.animationDuration = 4+Math.random()*6+"s";
      d.style.animationDelay   = Math.random()*6+"s";
      d.style.width = d.style.height = 2+Math.random()*4+"px";
      c.appendChild(d);
    }
  }

  // ── Firebase status badge ───────────────────────────────
  function injectFirebaseBadge() {
    const top = document.querySelector(".admin-top .brand p");
    if (top) top.innerHTML += ` &nbsp;<span style="background:#f59e0b;color:#000;font-size:0.7rem;padding:2px 8px;border-radius:999px;font-weight:700;vertical-align:middle;">🔥 Firebase Connected</span>`;
  }

  // ── Bind events ─────────────────────────────────────────
  function bindEvents() {
    $("loginBtn")?.addEventListener("click", login);
    $("adminPassword")?.addEventListener("keydown", (e) => { if (e.key==="Enter") login(); });

    $("saveBtn")?.addEventListener("click", save);

    $("addProjectBtn")?.addEventListener("click",   () => addItem("project"));
    $("addSkillBtn")?.addEventListener("click",     () => addItem("skill"));
    $("addCvSkillBtn")?.addEventListener("click",   () => addItem("cvSkill"));
    $("addCvProjectBtn")?.addEventListener("click", () => addItem("cvProject"));
    $("addEducationBtn")?.addEventListener("click", () => addItem("education"));
    $("addLanguageBtn")?.addEventListener("click",  () => addItem("language"));

    $("refreshJsonBtn")?.addEventListener("click", () => { collectForm(); refreshJson(); status("JSON refreshed."); });
    $("applyJsonBtn")?.addEventListener("click", applyJson);
    $("exportBtn")?.addEventListener("click", exportJson);
    $("resetBtn")?.addEventListener("click", resetDefaults);

    $("importFile")?.addEventListener("change", (e) => {
      if (e.target.files?.[0]) importJson(e.target.files[0]);
    });

    document.body.addEventListener("click", (e) => {
      const btn = e.target.closest("[data-delete]");
      if (!btn) return;
      deleteItem(btn.dataset.delete, btn.dataset.index);
    });
  }

  // ── Boot ────────────────────────────────────────────────
  document.addEventListener("DOMContentLoaded", () => {
    bindEvents();
    checkSession();
    makeParticles();
    injectFirebaseBadge();
  });

})();

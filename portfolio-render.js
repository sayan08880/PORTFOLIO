/* =========================================================
   PORTFOLIO CMS RENDERER
========================================================= */

(function () {
  const CMS = window.PortfolioCMS;
  if (!CMS) return;

  const E = CMS.escapeHtml;
  const P = CMS.clampPercent;

  function byId(id) {
    return document.getElementById(id);
  }

  function setText(id, value) {
    const el = byId(id);
    if (el) el.textContent = value || "";
  }

  function setImage(id, src, alt) {
    const el = byId(id);
    if (el && src) {
      el.src = src;
      el.alt = alt || "Portfolio image";
    }
  }

  function safeUrl(url, fallback = "#") {
    const value = String(url || "").trim();
    if (!value) return fallback;
    if (/^(https?:|mailto:|tel:)/i.test(value) || /^[\w./#-]+$/i.test(value)) return value;
    return fallback;
  }

  function renderCommon(data) {
    document.querySelectorAll("[data-site-logo]").forEach((el) => (el.textContent = data.site.logoText || "SAYAN"));
    document.querySelectorAll("[data-full-name]").forEach((el) => (el.textContent = data.site.fullName || "SAYAN MAHALANABISH"));
    setImage("siteProfileImage", data.site.profileImage, data.site.fullName);
    setImage("aboutProfileImage", data.site.profileImage, data.site.fullName);
    setImage("cvProfileImage", data.site.profileImage, data.cv.fullName || data.site.fullName);
  }

  function renderHome(data) {
    setText("homeGreeting", data.home.greeting);
    setText("homeTitle", data.home.title);
    setText("homeTagline", data.home.tagline);
    setText("homeButtonText", data.home.buttonText);
    const btn = byId("homeButton");
    if (btn) btn.onclick = () => window.open(safeUrl(data.home.buttonLink, "cv.html"), "_blank");
  }

  function renderAbout(data) {
    setText("aboutSubtitle", data.about.subtitle);
    setText("aboutHeading", data.about.heading);
    setText("aboutText", data.about.text);

    const facebook = byId("aboutFacebook");
    const instagram = byId("aboutInstagram");
    const github = byId("aboutGithub");
    const linkedin = byId("aboutLinkedin");
    if (facebook) facebook.href = safeUrl(data.site.facebook);
    if (instagram) instagram.href = safeUrl(data.site.instagram);
    if (github) github.href = safeUrl(data.site.github);
    if (linkedin) linkedin.href = safeUrl(data.site.linkedin);
  }

  function renderProjects(data) {
    const container = byId("projectsContainer");
    if (!container) return;
    container.innerHTML = (data.projects || []).map((project, index) => `
      <article class="project-card">
        <div class="project-img-container">
          <img src="${E(project.image || "logo.png")}" alt="${E(project.title || `Project ${index + 1}`)}" class="project-img">
        </div>
        <div class="project-info">
          <h3>${E(project.title)}</h3>
          <p>${E(project.description)}</p>
          <a href="${E(safeUrl(project.link))}" target="_blank" rel="noopener" class="github-link" title="${E(project.linkLabel || "Open Project")}">
            <img src="https://cdn-icons-png.flaticon.com/512/5968/5968898.png" alt="Project link" class="github-logo">
          </a>
        </div>
      </article>
    `).join("");
  }

  function renderSkills(data) {
    const grid = byId("skillsGrid");
    if (!grid) return;
    grid.innerHTML = (data.skills || []).map((skill) => {
      const percent = P(skill.percent);
      return `
        <article class="skill-card">
          <div class="skill-title">${E(skill.name)}</div>
          <div class="skill-bar-container">
            <div class="skill-bar" data-percent="${percent}%" data-target="${percent}%" style="width: 0%;"></div>
          </div>
        </article>
      `;
    }).join("");

    setTimeout(() => {
      grid.querySelectorAll(".skill-bar").forEach((bar) => {
        bar.style.width = bar.dataset.target || "0%";
      });
    }, 250);
  }

  function renderCv(data) {
    const cv = data.cv || {};
    setText("cvInitials", cv.navInitials || "SM");
    setText("cvTopName", data.site.fullName || cv.fullName);
    setText("cvBadge", cv.badge);
    const nameEl = byId("cvFullName");
    if (nameEl) nameEl.innerHTML = E(cv.fullName || "").replace(/\s+/, "<br>");
    setText("cvSubtitle", cv.subtitle);

    const phone = byId("cvPhone");
    const email = byId("cvEmail");
    const location = byId("cvLocation");
    if (phone) {
      phone.href = `tel:${String(cv.phone || "").replace(/\s+/g, "")}`;
      phone.innerHTML = `<i class="fa-solid fa-phone text-blue-400"></i> ${E(cv.phone)}`;
    }
    if (email) {
      email.href = `mailto:${E(cv.email)}`;
      email.innerHTML = `<i class="fa-solid fa-envelope text-blue-400"></i> ${E(cv.email)}`;
    }
    if (location) location.innerHTML = `<i class="fa-solid fa-location-dot text-blue-400"></i> ${E(cv.location)}`;

    setText("cvProfileHeading", cv.profileHeading);
    setText("cvProfileText", cv.profileText);
    setText("cvProfileTag", cv.profileTag);

    const skills = byId("cvSkillsList");
    if (skills) {
      skills.innerHTML = (cv.skills || []).map((skill) => {
        const percent = P(skill.percent);
        return `
          <div class="info-row skill-item" data-width="${percent}%">
            <div class="row-label"><i class="${E(skill.icon || "fa-solid fa-code")}"></i> ${E(skill.label)}</div>
            <div class="row-main"><h3>${E(skill.title)}</h3><div class="skill-line"><div class="skill-fill"></div></div></div>
            <span class="row-tag">${E(skill.level)}</span>
          </div>
        `;
      }).join("");
    }

    const projectGroups = byId("cvProjectGroups");
    if (projectGroups) {
      projectGroups.innerHTML = (cv.projectGroups || []).map((group) => `
        <div class="info-row">
          <div class="row-label"><i class="${E(group.icon || "fa-solid fa-diagram-project")}"></i> ${E(group.label)}</div>
          <div class="row-main"><h3>${E(group.title)}</h3><p>${E(group.description)}</p></div>
          <span class="row-tag">${E(group.tag)}</span>
        </div>
      `).join("");
    }

    const githubLink = byId("cvGithubLink");
    if (githubLink) githubLink.href = safeUrl(data.site.github);

    const education = byId("cvEducationList");
    if (education) {
      education.innerHTML = (cv.education || []).map((item) => `
        <div class="info-row">
          <div class="row-label"><i class="${E(item.icon || "fa-solid fa-graduation-cap")}"></i> ${E(item.label)}</div>
          <div class="row-main"><h3>${E(item.title)}</h3><p>${E(item.description)}</p></div>
          <span class="row-tag">${E(item.tag)}</span>
        </div>
      `).join("");
    }

    const languages = byId("cvLanguagesList");
    if (languages) {
      languages.innerHTML = (cv.languages || []).map((item) => `
        <div class="info-row">
          <div class="row-label">${E(item.label)}</div>
          <div class="row-main"><h3>${E(item.title)}</h3><p>${E(item.description)}</p></div>
          <span class="row-tag">${E(item.tag)}</span>
        </div>
      `).join("");
    }

    requestAnimationFrame(() => {
      document.querySelectorAll(".skill-item").forEach((item) => {
        const fill = item.querySelector(".skill-fill");
        if (fill) fill.style.width = item.dataset.width || "0%";
      });
    });
  }

  function makeParticles() {
    const container = byId("particles");
    if (!container || container.dataset.ready) return;
    container.dataset.ready = "true";
    for (let i = 0; i < 35; i++) {
      const dot = document.createElement("div");
      dot.classList.add("dot");
      dot.style.left = Math.random() * 100 + "vw";
      dot.style.top = Math.random() * 100 + "vh";
      dot.style.animationDuration = 4 + Math.random() * 6 + "s";
      dot.style.animationDelay = Math.random() * 6 + "s";
      dot.style.width = dot.style.height = 2 + Math.random() * 4 + "px";
      container.appendChild(dot);
    }
  }

  function setupReveal() {
    const revealItems = document.querySelectorAll(".reveal");
    if (!revealItems.length) return;
    const revealObserver = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) entry.target.classList.add("show");
      });
    }, { threshold: 0.15 });
    revealItems.forEach((item) => revealObserver.observe(item));
  }

  function renderAll() {
    const data = CMS.getData();
    renderCommon(data);
    renderHome(data);
    renderAbout(data);
    renderProjects(data);
    renderSkills(data);
    renderCv(data);
    makeParticles();
    setupReveal();
  }

  window.toggleMenu = function () {
    const navbar = byId("navbar");
    const hamburger = byId("hamburger");
    if (navbar) navbar.classList.toggle("open");
    if (hamburger) hamburger.classList.toggle("active");
  };

  document.addEventListener("DOMContentLoaded", renderAll);
  window.addEventListener("portfolio:data-changed", renderAll);
})();

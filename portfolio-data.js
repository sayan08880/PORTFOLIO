/* =========================================================
   PORTFOLIO CMS DATA
   Static-hosting version: data is saved in browser localStorage.
   For public/global updates, connect this same data to Firebase/Supabase.
========================================================= */

(function () {
  const STORAGE_KEY = "sayan_portfolio_cms_v1";

  const DEFAULT_DATA = {
    site: {
      logoText: "SAYAN",
      fullName: "SAYAN MAHALANABISH",
      profileImage: "sayan.jpg",
      logoImage: "logo.png",
      github: "https://github.com/sayan08880",
      linkedin: "https://www.linkedin.com/in/sayan-mahalanabish-4278571b6/",
      facebook: "https://www.facebook.com/sagar.mahalanabish",
      instagram: "https://www.instagram.com/sayan0880/"
    },
    home: {
      greeting: "Hi, I'm Sayan",
      title: "TECHNICAL EXPLORER",
      tagline: "TURNING IDEAS INTO FAST, CLEAN, AND FUNCTIONAL WEB APPLICATIONS WITH REAL-WORLD IMPACT",
      buttonText: "VIEW DETAILS",
      buttonLink: "cv.html"
    },
    about: {
      heading: "ABOUT ME",
      subtitle: "TECHNICAL EXPLORER & DEVELOPER",
      text: "I AM A TECHNOLOGY ENTHUSIAST FOCUSED ON WEB DEVELOPMENT AND PROGRAMMING, WITH HANDS-ON EXPERIENCE BUILDING REAL-WORLD PROJECTS. I HAVE A SOLID FOUNDATION IN JAVASCRIPT, HTML, CSS, PYTHON, AND C, AND I CONTINUOUSLY SHARPEN MY SKILLS BY CREATING PRACTICAL APPLICATIONS AND EXPLORING MODERN DEVELOPMENT PRACTICES."
    },
    projects: [
      {
        title: "WEATHER & WORLD CLOCK WEB APP",
        description: "A Vanilla JavaScript web app that shows live weather, sunrise/sunset, and world clock using APIs and real-time data.",
        image: "P1.png",
        link: "https://weather0880.netlify.app/",
        linkLabel: "Open Project"
      },
      {
        title: "HULK SEND LOCAL FILE SHARING SERVER",
        description: "A local file sharing server using Python that transfers files over Wi-Fi via QR scan—no internet or apps needed.",
        image: "P2.png",
        link: "https://github.com/sayan08880/FILE-SEND",
        linkLabel: "Open Project"
      },
      {
        title: "PRIVATE CHAT & ENCRYPTION",
        description: "A private encrypted web chat using Firebase Realtime Database with token rooms, live users, typing indicators, emojis, and a modern UI.",
        image: "P3.png",
        link: "https://chat080.netlify.app/",
        linkLabel: "Open Project"
      }
    ],
    skills: [
      { name: "WEB DESIGN", percent: 95 },
      { name: "DBMS", percent: 90 },
      { name: "PYTHON", percent: 88 },
      { name: "SQL", percent: 85 },
      { name: "PHP", percent: 62 },
      { name: "JAVA", percent: 58 },
      { name: "DSA", percent: 75 },
      { name: "UNIX", percent: 92 },
      { name: "IOT", percent: 95 }
    ],
    cv: {
      navInitials: "SM",
      badge: "Technical Expert",
      fullName: "Sayan Mahalanabish",
      subtitle: "Web Development • Programming • Real-World Projects",
      phone: "+91 6290688153",
      email: "dasguptasayan.080@gmail.com",
      location: "Barasat, Kolkata, West Bengal",
      profileHeading: "Technically skilled developer focused on practical applications",
      profileText: "Dedicated developer with a strong foundation in programming and web development. Experienced in building practical projects and solving real-world problems using modern technologies. Continuously learning and focused on creating meaningful and functional applications.",
      profileTag: "Developer",
      skills: [
        { icon: "fa-brands fa-python", label: "Python", title: "Programming logic and scripting", level: "Beginner", percent: 70 },
        { icon: "fa-solid fa-c", label: "C Programming", title: "Core programming concepts", level: "Beginner", percent: 65 },
        { icon: "fa-solid fa-globe", label: "HTML, CSS, JavaScript", title: "Frontend web development and UI design", level: "Intermediate", percent: 75 },
        { icon: "fa-brands fa-php", label: "PHP", title: "Basic server-side web development", level: "Beginner", percent: 65 },
        { icon: "fa-solid fa-terminal", label: "UNIX", title: "Command-line usage and system basics", level: "Intermediate", percent: 75 },
        { icon: "fa-brands fa-java", label: "Java Web Development", title: "Basic Java web application concepts", level: "Basic", percent: 50 }
      ],
      projectGroups: [
        {
          icon: "fa-brands fa-python",
          label: "Programming",
          title: "Python, Java & Backend Development",
          description: "AICHATBOT — Intelligent AI Chatbot in Python • FILE-SEND — File sharing utility • BACIS-GAME — Basic game development in Python • ENCODING-DECODING — Encryption/Decryption tool • MOBILE-NUMBER-INFORMATION — Phone number lookup tool • PASSWORD — Secure password manager in Java.",
          tag: "Backend"
        },
        {
          icon: "fa-solid fa-window-maximize",
          label: "Web Development",
          title: "HTML, CSS, JavaScript & PHP",
          description: "World-Weather — Real-time weather web app • CHATBOT — Interactive JavaScript chatbot • CALCULATOR — Full-featured calculator using PHP • PORTFOLIO — Personal portfolio website • NUMARIC-CODE-EDITOR — Online code editor template.",
          tag: "Frontend"
        }
      ],
      education: [
        { icon: "fa-solid fa-building-columns", label: "BCA", title: "Bachelor of Computer Applications", description: "Eminent College of Management & Technology", tag: "2023 – 2027" },
        { icon: "fa-solid fa-school", label: "HS", title: "Higher Secondary", description: "Barasat PCS Government High School", tag: "2011 – 2022" }
      ],
      languages: [
        { label: "Bengali", title: "Fluent communication", description: "Native-level understanding and speaking ability.", tag: "Fluent" },
        { label: "Hindi", title: "Fluent communication", description: "Comfortable speaking and understanding in daily communication.", tag: "Fluent" },
        { label: "English", title: "Basic communication", description: "Basic reading, writing, and speaking ability.", tag: "Basic" }
      ]
    }
  };

  function clone(value) {
    return JSON.parse(JSON.stringify(value));
  }

  function isObject(value) {
    return value && typeof value === "object" && !Array.isArray(value);
  }

  function mergeDeep(base, override) {
    const output = clone(base);
    if (!isObject(override)) return output;
    Object.keys(override).forEach((key) => {
      if (Array.isArray(override[key])) {
        output[key] = clone(override[key]);
      } else if (isObject(override[key]) && isObject(output[key])) {
        output[key] = mergeDeep(output[key], override[key]);
      } else {
        output[key] = override[key];
      }
    });
    return output;
  }

  function getData() {
    try {
      const saved = localStorage.getItem(STORAGE_KEY);
      if (!saved) return clone(DEFAULT_DATA);
      return mergeDeep(DEFAULT_DATA, JSON.parse(saved));
    } catch (error) {
      console.warn("Portfolio data could not be loaded. Defaults are being used.", error);
      return clone(DEFAULT_DATA);
    }
  }

  function saveData(data) {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(data, null, 2));
    window.dispatchEvent(new CustomEvent("portfolio:data-changed"));
  }

  function resetData() {
    localStorage.removeItem(STORAGE_KEY);
    window.dispatchEvent(new CustomEvent("portfolio:data-changed"));
  }

  function escapeHtml(value) {
    return String(value ?? "")
      .replaceAll("&", "&amp;")
      .replaceAll("<", "&lt;")
      .replaceAll(">", "&gt;")
      .replaceAll('"', "&quot;")
      .replaceAll("'", "&#039;");
  }

  function clampPercent(value) {
    const number = Number(value);
    if (Number.isNaN(number)) return 0;
    return Math.min(100, Math.max(0, Math.round(number)));
  }

  window.PortfolioCMS = {
    STORAGE_KEY,
    DEFAULT_DATA,
    getData,
    saveData,
    resetData,
    escapeHtml,
    clampPercent
  };
})();

/* =========================================================
   FIREBASE SYNC — auto-load server data on all pages
   Runs silently; if Firebase is unavailable, localStorage
   data (or defaults) are used as fallback.
========================================================= */
(function() {
  const FIREBASE_CONFIG = {
    apiKey: "AIzaSyDbESNVQuA9hG1XSA_f2Q4L6ldCCUw121w",
    authDomain: "portfolio-28b65.firebaseapp.com",
    projectId: "portfolio-28b65",
    storageBucket: "portfolio-28b65.firebasestorage.app",
    messagingSenderId: "475965194628",
    appId: "1:475965194628:web:fcd85ee57b354013f21dd1"
  };

  // Dynamically load Firebase and pull latest data into localStorage
  async function syncFromFirebase() {
    try {
      const { initializeApp } = await import("https://www.gstatic.com/firebasejs/10.12.2/firebase-app.js");
      const { getFirestore, doc, getDoc } = await import("https://www.gstatic.com/firebasejs/10.12.2/firebase-firestore.js");

      const app = initializeApp(FIREBASE_CONFIG, "portfolio-sync");
      const db  = getFirestore(app);
      const snap = await getDoc(doc(db, "portfolio", "cms"));

      if (snap.exists()) {
        const remote = JSON.parse(snap.data().payload);
        // Update localStorage so PortfolioCMS.getData() returns fresh data
        localStorage.setItem(window.PortfolioCMS.STORAGE_KEY, JSON.stringify(remote));
        // Notify portfolio-render.js to re-render
        window.dispatchEvent(new CustomEvent("portfolio:data-changed"));
      }
    } catch(e) {
      // Silently ignore — page uses localStorage / defaults
    }
  }

  // Only sync on non-admin pages
  if (!window.location.pathname.includes("admin")) {
    syncFromFirebase();
  }
})();

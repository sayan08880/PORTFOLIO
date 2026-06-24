# Portfolio Admin Panel

## Files added

- `admin.html` - admin dashboard
- `admin.css` - admin dashboard design
- `admin.js` - admin panel logic
- `portfolio-data.js` - default website data and localStorage CMS functions
- `portfolio-render.js` - dynamic renderer for Home, About, Projects, Skills, and CV pages

## Admin login

Open:

```text
admin.html
```

Default password:

```text
sayan123
```

Important: this password is only a front-end lock. Anyone can inspect the source code and see it. It is not real security.

## How the dynamic system works

This version is made for static hosting like GitHub Pages, Netlify, and Vercel static deploys.

When you save from the admin panel, data is stored in your browser using `localStorage`. The pages read from that saved data and update dynamically.

## Hard limitation

Static HTML cannot permanently update the live website for every visitor by itself. For real public admin updates, you need a database/backend like Firebase, Supabase, or a GitHub API workflow.

## Best static workflow

1. Open `admin.html`.
2. Edit your content.
3. Click **Save Changes**.
4. Click **Export JSON**.
5. Keep that exported JSON as backup.

For global online updates, connect this data object to Firebase/Supabase later.

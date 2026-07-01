<h1>Home page</h1>
<p>Welcome to my tiny PHP MVC app 👋</p>

<form method="get" action="/users" onsubmit="event.preventDefault(); const q = new URLSearchParams([...new FormData(this)].filter(([, v]) => v.trim())).toString(); location = '/users' + (q ? '?' + q : '')">
    <label>
        Name
        <input type="text" name="name" placeholder="e.g. Andrei">
    </label>
    <label>
        Min age
        <input type="number" name="age_min" min="0" max="150" placeholder="e.g. 18">
    </label>
    <label>
        Max age
        <input type="number" name="age_max" min="0" max="150" placeholder="e.g. 65">
    </label>
    <button type="submit">Search users</button>
</form>

<a href="/register">Register</a>

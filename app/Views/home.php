<h1>Home page</h1>
<p>Welcome to my tiny PHP MVC app 👋</p>

<form method="get" action="/users" onsubmit="event.preventDefault(); const q = new URLSearchParams([...new FormData(this)].filter(([, v]) => v.trim())).toString(); location = '/users' + (q ? '?' + q : '')">
    <label>
        Name
        <input type="text" name="name" placeholder="e.g. Andrei">
    </label>
    <label>
        Age
        <input type="number" name="age" min="0" max="150" placeholder="e.g. 20">
    </label>
    <button type="submit">Search users</button>
</form>

<p><a href="/users">See all users →</a></p>

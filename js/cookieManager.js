function setCookie(name, value)
{
    let expires = "";
    let days = 1;
    let date = new Date();
    date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
    expires = "; expires=" + date.toUTCString();
    document.cookie = name + "=" + value + "; path=/" + expires;
}

function getCookie(name)
{
    let cookies = document.cookie.split("; ");
    for (let cookie of cookies)
    {
        let [key, value] = cookie.split("=");
        if (key === name)
        {
            return value;
        }
    }
    return null;
}

function deleteCookie(name)
{
    document.cookie = name + "=; path=/; expires=Thu, 01 Jan 1970 00:00:00 UTC";
}

function updateMenu()
{
    let loginLink = document.querySelector("[data-login]");
    if (!loginLink)
    {
        loginLink = document.querySelector("[data-logout]")
    }

    let menu = loginLink.closest("li");
    if (menu)
    {
        if (getCookie('userName'))
        {
            if (getCookie('permission') == 0)
            {
                document.getElementById('add-anime').classList.remove('visually-hidden');
            }
            menu.innerHTML = `<button type="button" data-logout>Odhlásit se</button>`;
            document.querySelector("[data-logout]").addEventListener("click", function ()
                {
                    deleteCookie('userName');
                    deleteCookie('permission');
                    document.getElementById('add-anime').classList.add('visually-hidden');
                    updateMenu();
                }
            );
        } else {
            menu.innerHTML = `<a href="login.html" data-login>Přihlásit se</a>`;
            buttonVisibility();
        }
    }
}

window.setCookie = setCookie;
window.deleteCookie = deleteCookie;
window.getCookie = getCookie;
window.updateMenu = updateMenu;
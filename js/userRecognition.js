async function hashPassword(password, username)
{
    const encoder = new TextEncoder();
    const salt = encoder.encode(username);
    // const salt = crypto.getRandomValues(new Uint8Array(16));

    const keyMaterial = await crypto.subtle.importKey(
        'raw', encoder.encode(password), { name: 'PBKDF2' }, false, ['deriveBits']
    );

    const hashBuffer = await crypto.subtle.deriveBits(
        {
            name: 'PBKDF2',
            salt: salt,
            iterations: 100000,
            hash: 'SHA-256'
        },
        keyMaterial, 256
    );

    return Array.from(new Uint8Array(hashBuffer))
        .map(byte => byte.toString(16).padStart(2, '0'))
        .join('');
}


function checkExistingUser(userName, password)
{
    let users = localStorage.getItem('users');

    if (!users)
    {
        addToLocalStorage();
        users = localStorage.getItem('users');
    }

    users = JSON.parse(users);

        return hashPassword(password, userName).then(hashed =>
        {
            for (let user of users)
            {
                if (user.name === userName && user.password === hashed)
                {
                    setCookie('userName', userName);
                    setCookie('permission', user.permission);
                    return true;
                }
            }
            return false;
        }
    );
}

function addToLocalStorage()
{
    let users = [];
    Promise.all([
        hashPassword('Dd123456', 'Nuimi'),
        hashPassword('Dd123456', 'Nuimichan')
    ]).then(([hashed1, hashed2]) =>
        {
            users.push(
                {
                    name: 'Nuimi',
                    password: hashed1,
                    permission: 0,
                },
                {
                    name: 'Nuimichan',
                    password: hashed2,
                    permission: 1,
                }
            );

            localStorage.setItem('users', JSON.stringify(users));
        }
    );
    // hashPassword('Dd123456').then(hashed =>
    //     {
    //         users.push(
    //             {
    //                 name: 'Nuimichan',
    //                 password: hashed.hashedPassword,
    //                 permission: 1,
    //             }
    //         );
    //     }
    // );
    // let hashedPassword = await hashPassword('Dd123456');
    // users.push(
    //     {
    //         name: 'Nuimichan',
    //         password: hashedPassword.hashedPassword,
    //         permission: 1,
    //     }
    // );
}
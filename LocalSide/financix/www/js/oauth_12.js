var url = 'http://cuboide.polytechnique.fr/~luiz.bezerra-pinheiro';

var client_id = 'testclient';

var client_mdp = 'testpass';

function refresh_token() {
    if (localStorage.getItem("expires") !== null && localStorage.getItem("refresh_token") !== null) {
        var now = Math.floor(Date.now() / 1000);
        var refresh_expires = now + 2419200;
        var expires = localStorage.getItem("expires");
        // si le token a expiré mais pas le refresh_token (valide un mois), on récupère un token frais et un nouveau refresh_token
        if (now > expires && expires < refresh_expires) {
            $.ajax({
                method: "post",
                url: url + "/server/token.php",
                xhrFields: {
                    withCredentials: true
                },
                beforeSend: function (xhr) {
                    // login + mdp pour se connecter à l'API
                    xhr.setRequestHeader('Authorization', 'Basic ' + btoa(client_id + ':' + client_mdp));
                },
                data: {
                    grant_type: "refresh_token",
                    refresh_token: localStorage.getItem("refresh_token")
                },
                sucess: function (data) {
                    var new_expires = Math.floor(Date.now() / 1000);
                    new_expires += data.expires_in;
                    localStorage.setItem('acess_token', data.acess_token);
                    localStorage.setItem('expires', new_expires);
                    localStorage.setItem('refresh_token', data.refresh_token);
                },
                error: function (data) {
                    console.log(data);
                }
            });
        }

        //si le refresh_toke a expiré, on fait le ménage
        if (expires > refresh_expires) {
            localStorage.removeItem('acess_token');
            localStorage.removeItem('expires');
            localStorage.removeItem('refresh_token');
        }
    }
}

function login_1(username, password) {
    if (localStorage.getItem("access_token") === null && localStorage.getItem("expires") === null) {
        $.ajax({
            method: "post",
            url: url + "/server/token.php",
            xhrFields: {
                withCredentials: true
            },
            beforeSend: function (xhr) {
                xhr.setRequestHeader('Authorization', 'Basic ' + btoa(client_id + ':' + client_mdp));
            },
            data: {
                grant_type: "password",
                // login + mdp LDAP / ENEX (pas besoin de les stocker)
                username: username,
                password: password
            },
            success: function (data) {

                console.log(data);
                var expires = Math.floor(Date.now() / 1000);
                expires += data.expires_in;
                localStorage.setItem('access_token', data.access_token);
                localStorage.setItem('expires', expires);
                localStorage.setItem('refresh_token', data.refresh_token);
                location.reload();

            },
            error: function (data) {
                alert("Invalid username and password combination");
                console.log(data);
            }
        });
    }
}

function login() {
    username = $("#username").val();
    password = $("#password").val();
    //On stocke pas le mot de passe, on le lui demande toujours
    login_1(username, password);
}

function logout() {
    localStorage.clear();
    location.reload();
}

function inscription(currency) {
    var username = $("#user").val();
    var password1 = $("#password1").val();
    var password2 = $("#password2").val();
    var last_name = $("#last_name").val();
    var first_name = $("#first_name").val();
    var email = $("#email").val();
    var wallet_name = $("#wallet_name").val();
    // ajoutez ici les autres champs du formulaire

    $.ajax({
        method: "post",
        url: url + "/server/inscription.php",
        data: {
            username: username,
            password1: password1,
            password2: password2,
            last_name: last_name,
            first_name: first_name,
            email: email,
            wallet_name: wallet_name,
            currency: currency
                    // ajoutez ici les autres champs du formulaire
        },
        success: function (data) {
            console.log(data);
            if (data.success) {
                alert(data.success);
                login_1(username, password1);
                return true;
            } else {
                alert(data.error);
            }
        },
        error: function (data) {
            console.log(data);
            return false;
        }
    });
}
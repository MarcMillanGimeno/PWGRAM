/**
 * Created by Usuari on 15/03/2017.
 */


(function() {

        //Agafem dades
        var button = document.getElementById("signin_button");
        if(button){button.addEventListener("click", function (event) {
            var nom = document.getElementById("box_user_name").value;
            var birthday = document.getElementById("box_birthday").value;
            var email = document.getElementById("box_email").value;
            var pass1 = document.getElementById("box_password").value;
            var pass2 = document.getElementById("box_password2").value;
            var error = 0;
            var error_general = 0;
            var nom_aux = nom.split('');
            if (nom_aux.length> 20 || nom_aux.length == 0) {

                error = 1;
            } else {
                for (i = 0; i < nom_aux.length; i++) {
                    if (!(nom_aux[i] >= "A" && nom_aux[i] <= "Z") && !(nom_aux[i] >= "a" && nom_aux[i] <= "z") && !(nom_aux[i] >= "0" && nom_aux[i] <= "9")) {
                        error = 1;
                    }
                }
            }
            if (error == 1) {

                    var errN = document.getElementById("div_name");
                    var view_error = document.createElement("label");
                    view_error.id = nom;
                    view_error.textContent = "ERROR";
                view_error.style = "color:#B83519";
                    errN.appendChild(view_error);
                error_general = 1;
                }

            var email_aux = email.split('');
            var mail_ok = false;
            if (email_aux.length > 3) {

                for (i = 0; i < email_aux.length; i++) {
                    if (email_aux[i] == "@") {
                        mail_ok = true;
                        break;
                    }
                }
                if (mail_ok == true) {
                    mail_ok = false;
                    for (i; i < email_aux.length; i++) {
                        if (email_aux[i] == ".") {
                            mail_ok = true;
                            break;
                        }
                    }
                }
            }

            if (mail_ok == false) {

                var error_mail = document.getElementById("div_email");
                var view_error = document.createElement("label");
                view_error.id = email;
                view_error.textContent = "ERROR";
                view_error.style = "color:#B83519";
                error_mail.appendChild(view_error);
                error_general = 1;
            }

            var pass1_aux = pass1.split('');
            var min = 0;
            var maj = 0;
            var num = 0;

            for (i = 0; i < pass1_aux.length; i++) {
                if (pass1_aux[i] >= "A" && pass1_aux[i] <= "Z") {
                    maj++;
                } else {
                    if (pass1_aux[i] >= "a" && pass1_aux[i] <= "z") {
                        min++;
                    } else {
                        if (pass1_aux[i] >= "0" && pass1_aux[i] <= "9") {
                            num++;
                        }
                    }
                }
            }
            if ((maj < 1) || (min < 1) || (num < 1) || (pass1_aux.length < 6) || (pass1_aux.length > 12)) {

                    var errror_pass = document.getElementById("div_pass_1");
                    var view_error = document.createElement("label");
                    view_error.id = pass1;
                    view_error.textContent = "ERROR";
                    view_error.style = "color:#B83519";
                errror_pass.appendChild(view_error);

                error_general = 1;

            }else {

                if (pass1 != pass2) {
                    if(!document.getElementById("contraId2")) {

                        var errror_pass2 = document.getElementById("div_pass_2");
                        var n = document.createElement("label");
                        n.id = pass2;
                        n.textContent = "ERROR";
                        n.style = "color:#B83519";
                        errror_pass2.appendChild(n);
                    }
                    error_general = 1;

                }
            }

            if(error_general == 1){

                event.preventDefault();
            }
        });}else{
            console.log("NO VA");
        }

    }());

(function(){

    document.forms[0].reset()

    // 
    // Steps
    // 
    let step_now = 0;
    let step_unlocked = 1;
    let step_max = 5;
    let step_list = $('.step-list .btn');
    let step_item = $('.step-content .step-item');

    let btn_back = $('.btn[data-step="back"]');
    let btn_next = $('.btn[data-step="next"]');
    let btn_install = $('.btn[data-step="install"]');
    let try_again = $('.btn[data-step="try_again"]');
    let check_db = $('.btn[data-step="check_db"]');
    try_again.addClass("hide");
    check_db.addClass("hide");

    let config = {
        hostname: "",
        username: "",
        password: "",
        database: "",
        valid: false
    };

    let admin = {
        name: "",
        passwd: "",
        passwd2: "",
        email: "",
        valid: false
    };


    function valid_db(){
        let valid = 0;
        let error = "";
    
        config = {
            hostname: ($("#dbhost").val()).trim(),
            username: ($("#dbuser").val()).trim(),
            password: ($("#dbpass").val()).trim(),
            database: ($("#dbname").val()).trim()
        };
    
        if( config.hostname.length <= 0 ){
            valid += 1;
            error += "<p>Pole 'Host bazy danych' jest puste lub błędne</p>";
        }

        if( config.username.length <= 0 ){
            valid += 1;
            error += "<p>Pole 'Użytkownik bazy danych' jest puste lub błędne</p>";
        }

        if( config.password.length <= 0 ){
            valid += 1;
            error += "<p>Pole 'Hasło bazy danych' jest puste lub błędne</p>";
        }

        if( config.database.length <= 0 ){
            valid += 1;
            error += "<p>Pole 'Nazwa bazy danych' jest puste lub błędne</p>";
        }
    
        if( valid == 0 ){
            error = "";
            step_item.eq(2).find(".msg-box").html(error);
            step_item.eq(2).find(".msg-box").removeClass("show");

            config.valid = true;
            
            return true;
        }
        else{
            step_item.eq(2).find(".msg-box").html(error);
            step_item.eq(2).find(".msg-box").addClass("show");

            config.valid = false;

            return false;
        }
    }

    function admin_db(){
        let valid = 0;
        let error = "";
    
        admin = {
            name: ($("#adname").val()).trim(),
            passwd: ($("#adpasswd").val()).trim(),
            passwd2: ($("#adpasswd2").val()).trim(),
            email: ($("#ademail").val()).trim(),
        };
    
        if( admin.name.length <= 0 ){
            valid += 1;
            error += "<p>Pole 'Nazwa administratora' jest puste lub błędne</p>";
        }

        if( admin.passwd.length <= 0 ){
            valid += 1;
            error += "<p>Pole 'Hasło administratora' jest puste lub błędne</p>";
        }

        let score = score_password(admin.passwd);
        let input_meter = $("#adpasswd + p + .form-meter");


        input_meter.removeClass("strong good weak");
        if( score >= 80 ){
            input_meter.addClass("strong");
            input_meter.html("<p>Siła hasła: Silne</p>");
        }
        if( score >= 60 && score < 80 ){
            input_meter.addClass("good");
            input_meter.html("<p>Siła hasła: Dobre</p>");
        }
        if( score >= 30 && score < 60){
            input_meter.addClass("weak");
            input_meter.html("<p>Siła hasła: Słabe</p>");
        }
        if( score < 30){
            input_meter.addClass("weak");
            input_meter.html("<p>Siła hasła: Bardzo słabe</p>");
        }

        if( admin.passwd2.length <= 0 ){
            valid += 1;
            error += "<p>Pole 'Drugie hasło administratora' jest puste lub błędne</p>";
        }

        // Double password check
        if( admin.passwd != admin.passwd2 ){
            valid += 1;
            error += "<p><b>Hasła nie są identyczne</b></p>";
        }

        if( admin.email.length <= 0 || !validate_email(admin.email) ){
            valid += 1;
            error += "<p>Pole 'Adres email administraora' jest puste lub błędne</p>";
        }
    
        if( valid == 0 ){
            error = "";
            step_item.eq(3).find(".msg-box").html(error);
            step_item.eq(3).find(".msg-box").removeClass("show");
            
            admin.valid = true;
            return true;
        }
        else{
            step_item.eq(3).find(".msg-box").html(error);
            step_item.eq(3).find(".msg-box").addClass("show");

            admin.valid = false;
            return false;
        }
    }


    function step_unlock(){
        step_list.each(function(i, el){
            let blocked = (i > step_unlocked);
            $(this).toggleClass("blocked", blocked);
        });

        // Buttons
        btn_back.toggleClass("hide", (step_now < 1));
        btn_next.toggleClass("hide", (step_now >= step_max || step_now >= step_max-1));
        btn_next.removeAttr("disabled");
        btn_install.toggleClass("hide", (step_now < step_max-1));
    }

    function step_action(){
        let action_item = step_item.eq(step_now);

        switch(step_now){
            case 0:{
                // Przywitanie, odblokuj 2 krok
                if( step_unlocked < 2 ){
                    step_unlocked = 1;

                    step_list.eq(0).addClass("success");

                    step_unlock();
                }
                else{
                    btn_next.removeAttr("disabled");
                    step_list.eq(0).addClass("success");
                }
                break;
            }

            case 1:{
                if( step_unlocked < 2 ){
                    // Sprawdzenie plików, sprawdź pliki, w zależności od zwróconych danych zwróć informację
                    btn_next.attr("disabled", "disabled");

                    action_item.find(".msg-box.info").addClass("show");
                    action_item.find(".msg-box.success").removeClass("show");
                    action_item.find(".msg-box.error").removeClass("show");

                    let aj = $.ajax({
                        type: "POST",
                        url: "checkfile.php",
                        success: function (resp) {

                            action_item.find("h2 .loading").hide();

                            // Kiedy ok
                            if( resp.status ){
                                action_item.find(".msg-box.info").removeClass("show");
                                action_item.find(".msg-box.success").html(resp.msg);
                                action_item.find(".msg-box.success").addClass("show");

                                step_unlocked = 2;
                                step_list.eq(1).addClass("success");

                                step_unlock();

                                btn_next.removeClass("hide");
                                try_again.addClass("hide");
                            }
                            // Kiedy nok
                            else{
                                action_item.find(".msg-box.info").removeClass("show");
                                action_item.find(".msg-box.error").html(resp.msg);
                                action_item.find(".msg-box.error").addClass("show");

                                step_unlock();

                                try_again.removeClass("hide");
                                btn_next.addClass("hide");
                            }
                        }
                    });
                }
                else{
                    btn_next.removeAttr("disabled");
                }

                break;
            }

            case 2:{
                if( step_unlocked < 3 ){
                    btn_next.attr("disabled", "disabled");

                    step_item.eq(2).find(".form-group input").on("input", function(){
                        if( valid_db() ){
                            
                            step_unlocked = 3;

                            step_list.eq(2).addClass("success");
                            step_list.eq(2).removeClass("error");

                            step_unlock();
                        }
                        else{
                            step_unlocked = 2;

                            step_list.eq(2).addClass("error");
                            step_list.eq(2).removeClass("success");

                            step_unlock();

                            btn_next.attr("disabled", "disabled");
                        }
                    });
                }
                else{
                    btn_next.removeAttr("disabled");
                }

                break;
            }
            case 3:{
                if(  step_unlocked < 4){
                    btn_next.attr("disabled", "disabled");

                    step_item.eq(3).find(".form-group input").on("input", function(){
                        if( admin_db() ){
                            
                            step_unlocked = 4;

                            step_list.eq(3).addClass("success");
                            step_list.eq(3).removeClass("error");

                            step_unlock();
                        }
                        else{
                            step_unlocked = 3;

                            step_list.eq(3).addClass("error");
                            step_list.eq(3).removeClass("success");

                            step_unlock();

                            btn_next.attr("disabled", "disabled");
                        }
                    });
                }
                else{
                    btn_next.removeAttr("disabled");
                }

                break;
            }
            case 4:{
                step_list.eq(4).addClass("success");

                step_unlock();

                break;
            }
        }
    }

    function set_step(){
        step_list.removeClass("active");
        step_list.eq(step_now).addClass("active");

        step_item.hide();
        step_item.eq(step_now).show();

        // Action
        step_action();
    }
    set_step();

    step_list.on("click", function(e){
        let step = parseInt($(this).data("step"))-1;

        if( step <= step_unlocked ){
            step_now = step;

            set_step();
        }

        e.preventDefault();
        return false;
    });

    btn_next.on("click", function(e){
        if( step_now < step_unlocked ){
            step_now++;

            set_step();
        }
        
        e.preventDefault();
        return false;
    });

    btn_back.on("click", function(e){
        if( step_now > 0 ){
            step_now--;

            set_step();
        }

        e.preventDefault();
        return false;
    });

    try_again.on("click", function(e){

        step_action();

        e.preventDefault();
        return false;
    });

    btn_install.on("click", function(e){
        if( config.valid && admin.valid ){
            step_item.eq(4).find(".msg-box").removeClass("show");
            $("#form-progress").show();
            $("#form-progress").html('<div class="loading"></div> Trwa instalacja DIVShop.pro. Proszę czekać i nie odświeżać strony.');

            btn_install.attr("disabled", "disabled");

            // Start install
            $.ajax({
                type: "post",
                url: "install.php",
                data: {
                    template: 3,
                    url: base_url,
                    hostname: config.hostname,
                    username: config.username,
                    password: config.password,
                    database: config.database,
                    
                    admin_login: admin.name,
                    admin_pass: admin.passwd,
                    admin_pass2: admin.passwd2,
                    admin_email: admin.email
                },
                success: function (resp) {
                    if( resp.status ){

                        btn_install.removeAttr("disabled");
                        btn_install.addClass("hide");

                        console.log(resp);

                        $("#main-wrap").hide();
                        $("#success-wrap").show();
                        $("#form-progress").hide();
                    }
                    // Kiedy nok
                    else{
                        $("#form-progress").hide();
                        step_item.eq(4).find(".msg-box.error").html(resp.msg);
                        step_item.eq(4).find(".msg-box.error").addClass("show");

                        btn_install.removeAttr("disabled");
                    }
                },
            });
        }
        else{
            step_item.eq(4).find(".msg-box").addClass("show");
        }

        e.preventDefault();
        return false;
    });

})();
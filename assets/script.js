// [ ] disable when processing
// [ ] check setinterval
class Form {
    constructor() {
        this.$modalContainer = $(".modal-container");
        this.$form = $("#newsletter-form");
        this.$submitButton = $("#submit");
        this.$nameField = $("#name");
        this.$emailField = $("#email");
        this.$typeField = $("#type");
        this.$formIdField = $("#form_id");
        this.isLoading = false;
        this.$notificationContainer = $(
            '<div class="alert" id="notification" role="alert"></div>'
        );
        this.isNotificationActive = false;
        this.events();
    }

    //events
    events() {
        this.$form.on("submit", this.handleSubmit.bind(this));
    }

    //methods
    handleSubmit(event) {
        $("#submit").prop("disabled", true);

        let formData = {
            name: this.$nameField.val(),
            email: this.$emailField.val(),
            type: this.$typeField.val(),
            form_id: this.$formIdField.val(),
        };

        $.ajax({
            type: "POST",
            url: "http://localhost/paradox/process.php",
            data: formData,
            dataType: "json",
            context: document.body,
            encode: true,
        }).done((data) => this.doneHandler(data));

        event.preventDefault();
    }

    resetFields() {
        this.$emailField.val("").removeAttr("value");
        this.$nameField.val("").removeAttr("value");
    }

    removeNotification(className) {
        setInterval(() => {
            this.isNotificationActive = false;
            this.$notificationContainer
                .removeClass(className)
                .remove()
                .html("");
        }, 3300);
    }

    doneHandler(data) {
        this.isNotificationActive = true;

        if (this.isNotificationActive) {
            if (data.success) {
                this.$notificationContainer
                    .appendTo(this.$modalContainer)
                    .addClass("alert-success")
                    .html(data.message);

                this.resetFields();
                this.removeNotification("alert-success");
            } else {
                this.$notificationContainer
                    .appendTo(this.$modalContainer)
                    .addClass("alert-danger");

                if (data.errors.length >= 0) {
                    let str = "";

                    data.errors.forEach((element) => {
                        str += '<p class="mb-0">' + element + "</p>";
                    });

                    this.$notificationContainer.html(str);
                }

                this.removeNotification("alert-danger");
            }
        }

        $("#submit").delay(1000).prop("disabled", false);
    }
}

const subscription = new Form();

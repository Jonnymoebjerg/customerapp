<div id="modalForgotLogin" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Forgot something? <small>- Let's figure it out!</small></h4>
            </div>
            <div class="modal-body">
                <p>If you forgot your login details, we can send them to your email address. Insert it in the form and hit "Send", then you will have it in your inbox soon.</p>
                <div class="form-group">
                    <input type="email" class="form-control" id="forgotInputEmail" placeholder="Email">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="fgtLogin">Send</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<script src="assets/js/jquery.min.js"></script>
<script src="assets/bootstrap/js/bootstrap.min.js"></script>
<script>
    $('#fgtLogin').click(function() {
        var emailForgot = document.getElementById("forgotInputEmail").value;
        console.log(emailForgot);
        $.ajax({
            type: "POST",
            url: "php/getLoginInfo.php",
            datatype: "text",
            data: {emailForgot: emailForgot},
            success: function () {
                alert("Mail sent!");
            }
        });
    });
</script>
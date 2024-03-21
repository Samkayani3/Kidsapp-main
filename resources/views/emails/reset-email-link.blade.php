<!doctype html>
<html lang="en">
<style>
    button{
        padding: 10px 30px;
        background-color: #c80062;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
</style>
<!-- <body>
    <h3>Password Reset</h3>
    <p>Email: {{ $display_email }}</p>
    <p>Reset URL: <a href="{{ $reset_url }}">Click Here</a></p>
    <p>Thank you!</p>

</body> -->
<h2>Password Reset</h2>
    <p>Email: <span id="display_email"></span></p>
    <button id="reset_button">Reset Password</button>
    <p>Thank you!</p>

    <script>
        var display_email = "{{ $display_email }}";
        var reset_url = "{{ $reset_url }}";

        document.getElementById("display_email").innerText = display_email;
        document.getElementById("reset_button").addEventListener("click", function() {

            window.location.href = reset_url;
        });
    </script>
</html>

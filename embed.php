<?php
header("Content-Type: application/javascript");
?>
(function() {
    var css = document.createElement("link");
    css.rel = "stylesheet";
    css.href = "/ai/widget.css";
    document.head.appendChild(css);
    
    var script = document.createElement("script");
    script.src = "/ai/widget.js";
    document.head.appendChild(script);
    
    fetch("/ai/widget.php")
        .then(function(response) {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(function(html) {
            var div = document.createElement("div");
            div.innerHTML = html;
            document.body.appendChild(div);
        })
        .catch(function(error) {
            console.error('Error loading AI widget:', error);
        });
})();

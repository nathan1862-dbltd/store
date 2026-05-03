document.getElementById("skinForm").addEventListener("submit", async function(e){

  e.preventDefault();

  const formData = new FormData(this);

  document.getElementById("loading").classList.remove("hidden");
  document.getElementById("result").innerHTML = "";

  const response = await fetch("/skin-analyzer/analyze.php",{
    method:"POST",
    body:formData
  });

  const data = await response.text();

  document.getElementById("loading").classList.add("hidden");

  document.getElementById("result").innerHTML = data;

});

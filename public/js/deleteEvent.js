document.addEventListener("DOMContentLoaded", () => {
  // Selecciona todos los botones de eliminación
  document.querySelectorAll(".darsebaja").forEach((button) => {
    button.addEventListener("click", function () {
      const itemId = this.getAttribute("data-id")

      const folderName = "tp"
      const url = `${window.location.protocol}//${window.location.host}/${folderName}`

      // Enviar solicitud AJAX para eliminar el ítem
      fetch(`../src/procesoEliminar.php`, {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: new URLSearchParams({ id: itemId }),
      })
        .then((response) => response.json())
        .then((data) => {
          this.disabled = true
          window.location.reload()
        })
        .catch((error) => {
          console.error("Error en la solicitud AJAX:", error)
          window.location.reload()
        })
    })
  })
})


document.getElementById("notificationFilter").addEventListener("change", function () {
    const filterValue = this.value;
    const notificationItems = document.querySelectorAll(".list-group-item");

    notificationItems.forEach(function (item) {
        const itemType = item.getAttribute("data-type");
        if (filterValue === "all" || filterValue === itemType) {
            item.style.display = "block";
        } else {
            item.style.display = "none";
        }
    });
});

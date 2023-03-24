var year = document.getElementById("year").value;

$.ajax({
    url: "/getYear/" + year,
    type: "get",
    dataType: "json",
    data: {},
    success: function (data) {
        alert("success");
    },
});

function showMonths() {
    $year = document.getElementById("year").value;
}

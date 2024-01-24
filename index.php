<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evaluation Data</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
<h1 class="text-center">DATA TABLE</h1>
    <table class="table table-bordered table-hover" id="evaluationTable">
        <thead>
            <tr>
                <th>Name</th>
                <th>Age</th>
                <th>Address</th>
                <th>Working Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <!-- Table rows will be populated here -->
        </tbody>
    </table>
</div>

<script>
    // Function to convert UTC date to Asia/Kuala_Lumpur time
    function convertToKualaLumpurTime(utcDate) {
        return new Date(utcDate).toLocaleString("en-MY", { timeZone: "Asia/Kuala_Lumpur" });
    }

    // Function to populate the HTML table
    function populateTable(data) {
        var tableBody = document.getElementById("evaluationTable").getElementsByTagName('tbody')[0];

        data.forEach(function(person) {
            var row = document.createElement("tr");
            row.innerHTML = "<td>" + person.name + "</td>" +
                            "<td>" + calculateAge(person.date_of_birth) + "</td>" +
                            "<td>" + person.address + "</td>" +
                            "<td>" + (person.working_status ? "Working" : "Not Working") + "</td>";

            var exportButton = document.createElement("button");
            exportButton.classList.add("btn", "btn-primary");
            exportButton.textContent = "Export CSV";
            exportButton.addEventListener("click", function() {
                exportToCSV(person.name, person.evaluation);
            });

            var cell = document.createElement("td");
            cell.appendChild(exportButton);
            row.appendChild(cell);

            tableBody.appendChild(row);
        });
    }

    // Function to calculate age based on date of birth
    function calculateAge(dob) {
        var birthDate = new Date(dob);
        var currentDate = new Date();
        var age = currentDate.getFullYear() - birthDate.getFullYear();
        return age;
    }

    // Function to export data to CSV
    function exportToCSV(personName, evaluation) {
        var csvContent = "Title,Test,Score,Evaluated At\n";
        evaluation.score.forEach(function(test) {
            csvContent += evaluation.title + "," +
                          Object.keys(test)[0] + "," +
                          test[Object.keys(test)[0]] + "," +
                          convertToKualaLumpurTime(evaluation.created_at) + "\n";
        });

        var blob = new Blob([csvContent], { type: "text/csv;charset=utf-8" });
        var link = document.createElement("a");

        link.href = window.URL.createObjectURL(blob);
        link.download = personName.toLowerCase().replace(' ', '_') + "_evaluation.csv";
        link.click();
    }

    // Fetch data from an external source
    fetch('evaluation-20190711.json')
        .then(response => response.json())
        .then(data => {
            populateTable(data.data);
        })
        .catch(error => console.error('Error fetching data:', error));
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

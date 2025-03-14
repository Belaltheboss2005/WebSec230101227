@extends('layouts.master')

@section('title', 'Calculator & GPA Simulator')

@section('content')

<!-- SIMPLE CALCULATOR -->
<div class="container">
    <h3>Simple Calculator</h3>
    <form>
        <input type="number" id="num1" class="form-control" placeholder="Enter first number">
        <input type="number" id="num2" class="form-control mt-2" placeholder="Enter second number">
        <select id="operation" class="form-control mt-2">
            <option value="+">+</option>
            <option value="-">-</option>
            <option value="*">*</option>
            <option value="/">/</option>
        </select>
        <button type="button" class="btn btn-success mt-2" onclick="calculate()">Calculate</button>
    </form>
    <h4 class="mt-3 text-primary" id="calcResult">Result: --</h4>
</div>

<!-- GPA SIMULATOR -->
<div class="container mt-5">
    <h3>GPA Simulator</h3>
    <p>Add courses and assign grades to calculate your GPA.</p>

    <!-- Add Course Form -->
    <div class="row">
        <div class="col-md-3">
            <input type="text" id="courseCode" class="form-control" placeholder="Course Code">
        </div>
        <div class="col-md-3">
            <input type="text" id="courseTitle" class="form-control" placeholder="Course Title">
        </div>
        <div class="col-md-2">
            <input type="number" id="creditHours" class="form-control" placeholder="Credit Hours">
        </div>
        <div class="col-md-2">
            <select id="courseGrade" class="form-control">
                <option value="4">A</option>
                <option value="3.7">A-</option>
                <option value="3.3">B+</option>
                <option value="3.0">B</option>
                <option value="2.7">B-</option>
                <option value="2.3">C+</option>
                <option value="2.0">C</option>
                <option value="1.7">C-</option>
                <option value="1.3">D+</option>
                <option value="1.0">D</option>
                <option value="0">F</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-primary" onclick="addCourse()">Add Course</button>
        </div>
    </div>

    <!-- Course Table -->
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Course Code</th>
                <th>Course Title</th>
                <th>Credit Hours</th>
                <th>Grade</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="gpaTable"></tbody>
    </table>

    <button type="button" class="btn btn-success" onclick="calculateGPA()">Calculate GPA</button>
    <h4 class="mt-3 text-primary" id="gpaResult">Your GPA: --</h4>
</div>

<script>
    function calculate() {
        let num1 = parseFloat(document.getElementById("num1").value);
        let num2 = parseFloat(document.getElementById("num2").value);
        let operation = document.getElementById("operation").value;
        let result;
        if (operation === "+") result = num1 + num2;
        else if (operation === "-") result = num1 - num2;
        else if (operation === "*") result = num1 * num2;
        else if (operation === "/") result = num1 / num2;
        else result = "Invalid Operation";

        document.getElementById("calcResult").innerText = "Result: " + result;
    }

    let courses = [];

    function addCourse() {
        let code = document.getElementById("courseCode").value;
        let title = document.getElementById("courseTitle").value;
        let credit = parseFloat(document.getElementById("creditHours").value);
        let grade = parseFloat(document.getElementById("courseGrade").value);

        if (!code || !title || isNaN(credit) || isNaN(grade)) {
            alert("Please fill all fields correctly.");
            return;
        }

        let course = { code, title, credit, grade };
        courses.push(course);
        updateCourseTable();
        resetForm();
    }

    function updateCourseTable() {
        let tableBody = document.getElementById("gpaTable");
        tableBody.innerHTML = "";
        courses.forEach((course, index) => {
            let row = `<tr>
                <td><input type="text" class="form-control" value="${course.code}" onchange="editCourse(${index}, 'code', this.value)"></td>
                <td><input type="text" class="form-control" value="${course.title}" onchange="editCourse(${index}, 'title', this.value)"></td>
                <td><input type="number" class="form-control" value="${course.credit}" onchange="editCourse(${index}, 'credit', parseFloat(this.value))"></td>
                <td>
                    <select class="form-control" onchange="editCourse(${index}, 'grade', parseFloat(this.value))">
                        <option value="4" ${course.grade == 4 ? "selected" : ""}>A</option>
                        <option value="3.7" ${course.grade == 3.7 ? "selected" : ""}>A-</option>
                        <option value="3.3" ${course.grade == 3.3 ? "selected" : ""}>B+</option>
                        <option value="3.0" ${course.grade == 3.0 ? "selected" : ""}>B</option>
                        <option value="2.7" ${course.grade == 2.7 ? "selected" : ""}>B-</option>
                        <option value="2.3" ${course.grade == 2.3 ? "selected" : ""}>C+</option>
                        <option value="2.0" ${course.grade == 2.0 ? "selected" : ""}>C</option>
                        <option value="1.7" ${course.grade == 1.7 ? "selected" : ""}>C-</option>
                        <option value="1.3" ${course.grade == 1.3 ? "selected" : ""}>D+</option>
                        <option value="1.0" ${course.grade == 1.0 ? "selected" : ""}>D</option>
                        <option value="0" ${course.grade == 0 ? "selected" : ""}>F</option>
                    </select>
                </td>
                <td>
                    <button class="btn btn-danger" onclick="removeCourse(${index})">Remove</button>
                </td>
            </tr>`;
            tableBody.innerHTML += row;
        });
    }

    function editCourse(index, field, value) {
        courses[index][field] = value;
    }

    function removeCourse(index) {
        courses.splice(index, 1);
        updateCourseTable();
    }

    function calculateGPA() {
        if (courses.length === 0) {
            alert("No courses added.");
            return;
        }

        let totalCredits = 0;
        let totalPoints = 0;

        courses.forEach(course => {
            totalCredits += course.credit;
            totalPoints += course.credit * course.grade;
        });

        let gpa = totalPoints / totalCredits;
        document.getElementById("gpaResult").innerText = "Your GPA: " + gpa.toFixed(2);
    }

    function resetForm() {
        document.getElementById("courseCode").value = "";
        document.getElementById("courseTitle").value = "";
        document.getElementById("creditHours").value = "";
        document.getElementById("courseGrade").value = "4";
    }
</script>

@endsection

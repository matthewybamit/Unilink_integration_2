function myFunction(x) {
    x.classList.toggle("change");
  }

  function myFunction(x) {
    // Toggle the icon to change into "X"
    x.classList.toggle("change");
    
    // Toggle the curtain menu's height to reveal or hide it
    var menu = document.getElementById("curtainMenu");
    if (menu.classList.contains("curtain-open")) {
        menu.classList.remove("curtain-open");
    } else {
        menu.classList.add("curtain-open");
    }
}



function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const collapseIcon = document.getElementById('collapseIcon');
    
    sidebar.classList.toggle('collapsed'); // Toggle the collapsed class

    // Toggle the icon rotation class
    collapseIcon.classList.toggle('collapsed-icon');
}



function toggleCurtainMenu(x) {
    var menu = document.getElementById('curtainMenu');
    menu.classList.toggle('open');

    // Optional: Change the hamburger icon into a "close" icon when the menu is open
    x.classList.toggle('change');
}





document.addEventListener("DOMContentLoaded", () => {
    const calendar = document.getElementById("calendar");
    const addPlanBtn = document.getElementById("addPlanBtn");
    const planFormContainer = document.getElementById("planFormContainer");
    const closeFormBtn = document.getElementById("closeFormBtn");
    const planForm = document.getElementById("planForm");
    
    // Generate calendar for current month
    const generateCalendar = (year, month) => {
        calendar.innerHTML = "";
        let daysInMonth = new Date(year, month + 1, 0).getDate();
        
        for (let day = 1; day <= daysInMonth; day++) {
            let dayDiv = document.createElement("div");
            dayDiv.classList.add("day");
            dayDiv.setAttribute('data-day', day); // Set the data-day attribute
            dayDiv.textContent = day; // Optional: Display day number in the center
            calendar.appendChild(dayDiv);
        }
    };
    
    // Set current month and year
    let currentMonth = new Date().getMonth();
    let currentYear = new Date().getFullYear();
    generateCalendar(currentYear, currentMonth);

    // Open form on button click
    addPlanBtn.addEventListener("click", () => {
        planFormContainer.style.display = "flex";
    });

    // Close form
    closeFormBtn.addEventListener("click", () => {
        planFormContainer.style.display = "none";
    });

    // Handle form submission
    planForm.addEventListener("submit", (event) => {
        event.preventDefault();

        const planLabel = document.getElementById("planLabel").value;
        const planDate = document.getElementById("planDate").value;
        const planTime = document.getElementById("planTime").value;

        if (planLabel && planDate && planTime) {
            const selectedDay = new Date(planDate).getDate();
            const dayDivs = document.querySelectorAll(".calendar .day");

            dayDivs.forEach(day => {
                if (parseInt(day.textContent) === selectedDay) {
                    const plan = document.createElement("div");
                    plan.textContent = `${planLabel} @ ${planTime}`;
                    plan.style.backgroundColor = "#ffc107";
                    plan.style.padding = "5px";
                    plan.style.marginTop = "5px";
                    plan.style.borderRadius = "5px";
                    day.appendChild(plan);
                }
            });

            // Close form after submission
            planFormContainer.style.display = "none";
            planForm.reset();
        }
    });
});








function animateOnScroll() {
    const elementsToShow = document.querySelectorAll('.hidden-left, .hidden-right');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('show');
            } else {
                entry.target.classList.remove('show'); // Remove class when out of view
            }
        });
    });

    elementsToShow.forEach((element) => {
        observer.observe(element);
    });
}

document.addEventListener('DOMContentLoaded', animateOnScroll);

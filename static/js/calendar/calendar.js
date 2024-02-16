
// const last_month = document.getElementById('last_month');
// const next_month = document.getElementById('next_month');

// for (let i = 0; i < 5; i++) {}



// function week_info_appear(week, week_info) {
//     for (let i = 0; i < week.length; i++) {
//         week[i].addEventListener("click", function() {
//             if (week_info[i].classList[0] == "d-none") {
//                 week_info[i].classList[0].splice(0, 1);
//             } else {
//                 week_info[i].classList.unshift("d-none");
//             }
//             // console.log(week_info[i].classList[0]);
//         });
//     }
// }

const week_array = {
    "week": [
        document.getElementsByName("week_0"),
        document.getElementsByName("week_1"),
        document.getElementsByName("week_2"),
        document.getElementsByName("week_3"),
        document.getElementsByName("week_4"),
    ],
    "week_info": [
        document.getElementsByName("week_info_0"),
        document.getElementsByName("week_info_1"),
        document.getElementsByName("week_info_2"),
        document.getElementsByName("week_info_3"),
        document.getElementsByName("week_info_4"),
    ],
};

for (let w = 0; w < week_array["week"].length; w++) {
    for (let d = 0; d < week_array["week"][w].length; d++) {
        week_array["week"][w][d].addEventListener("click", function() {
            week_array["week_info"][w].forEach(week_info => {
                if (week_info.classList[0] == "d-none") {
                    week_info.className = "d-inline-block w-100 daily-info";
                    // week_array["week_info"][w][d].classList[0] = "d-inline-block";
                    // week_array["week_info"][w][d].classList.splice(0, 1);
                } else {
                    week_info.className = "d-none w-100 daily-info";
                    // week_array["week_info"][w][d].classList[0] = "d-none";
                    // week_array["week_info"][w][d].classList.unshift("d-none");
                }
                console.log(week_info.classList[0]);
                // console.log(week_info.className);
            });

            // if (week_array["week_info"][w][d].classList[0] == "d-none") {
            //     week_array["week_info"][w][d].className = "d-inline-block w-100";
            //     // week_array["week_info"][w][d].classList[0] = "d-inline-block";
            //     // week_array["week_info"][w][d].classList.splice(0, 1);
            // } else {
            //     week_array["week_info"][w][d].className = "d-none w-100";
            //     // week_array["week_info"][w][d].classList[0] = "d-none";
            //     // week_array["week_info"][w][d].classList.unshift("d-none");
            // }
            // console.log(week_array["week_info"][w][d].classList[0]);
            // console.log(week_array["week_info"][w][d].className);
        });
    }
}


// const week_0 = document.getElementsByName("week_0");
// const week_1 = document.getElementsByName("week_1");
// const week_2 = document.getElementsByName("week_2");
// const week_3 = document.getElementsByName("week_3");
// const week_4 = document.getElementsByName("week_4");

// const week_info_0 = document.getElementsByName("week_info_0");
// const week_info_1 = document.getElementsByName("week_info_1");
// const week_info_2 = document.getElementsByName("week_info_2");
// const week_info_3 = document.getElementsByName("week_info_3");
// const week_info_4 = document.getElementsByName("week_info_4");





// for (let i = 0; i < week_0.length; i++) {
//     week_0[i].addEventListener("click", function() {
        
//         week_info_0[i].classList[0].splice(0, 1);
//         // console.log(week_info_0[i].classList[0]);
//     });
// }

// for (let i = 0; i < week_1.length; i++) {
//     week_1[i].addEventListener("click", function() {
        
//         week_info_1[i].classList[0].splice(0, 1);
//         // console.log(week_info_1[i].classList[0]);
//     });
// }

// for (let i = 0; i < week_2.length; i++) {
//     week_2[i].addEventListener("click", function() {
        
//         week_info_2[i].classList[0].splice(0, 1);
//         // console.log(week_info_2[i].classList[0]);
//     });
// }

// for (let i = 0; i < week_3.length; i++) {
//     week_3[i].addEventListener("click", function() {
        
//         week_info_3[i].classList[0].splice(0, 1);
//         // console.log(week_info_3[i].classList[0]);
//     });
// }

// for (let i = 0; i < week_4.length; i++) {
//     week_4[i].addEventListener("click", function() {
        
//         week_info_4[i].classList[0].splice(0, 1);
//         // console.log(week_info_4[i].classList[0]);
//     });
// }
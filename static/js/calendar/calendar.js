const week_array = [
    document.getElementsByName("week_0"),
    document.getElementsByName("week_1"),
    document.getElementsByName("week_2"),
    document.getElementsByName("week_3"),
    document.getElementsByName("week_4"),
];

const info_array = [
    document.getElementsByName("in_info"),
    document.getElementsByName("ex_info"),
    document.getElementsByName("pt_info"),
]

const week_info = document.getElementsByName("week_info");


function info_tag_create(name, val, unit) {
    let tag = document.createElement('div');
    tag.className = "mb-2 text-center";

    let nameTxt = document.createElement('span');
    nameTxt.className = "text-nowrap";
    nameTxt.textContent = name;
    tag.appendChild(nameTxt);

    let val_unitTxt = document.createElement('span');
    val_unitTxt.className = "d-block d-sm-inline-block mx-2";
    val_unitTxt.textContent = String(val) + unit;
    tag.appendChild(val_unitTxt);

    return(tag);
}

function daily_info_appear(w, d) {
    for (let i = 0; i < info_data.length; i++) {
        info_array[i][w].innerHTML = null;

        if ("日付" in info_data[i] && info_data[i]["日付"].some(value => value == d)) {
            for (let j = 0; j < info_data[i]["日付"].length; j++) {
                if (info_data[i]["日付"][j] == d) {
                    let info_unit = "";

                    switch (i) {
                        case 0:
                        case 1:
                            info_unit = "円"; break;
                        case 2:
                            info_unit = "pt"; break;
                        // default:
                        //     break;
                    }

                    info_array[i][w].appendChild(info_tag_create(info_data[i]["記録内容"][j], info_data[i]["記録情報"][j], info_unit));
                }
            }
        } else {
            var info_tag = document.createElement('div');
            info_tag.className = "text-center mt-3 text-secondary";
            info_tag.textContent = "記録なし";

            info_array[i][w].appendChild(info_tag);
        }
    }
}


const week_shift = 7 - week_array[0].length;
let this_day = -1;
let last_day = -1;

for (let week = 0; week < week_array.length; week++) {
    for (let day = 0; day < week_array[week].length; day++) {
        week_array[week][day].addEventListener("click", function() {
            this_day = week * 7 + day;
            if (week > 0) this_day -= week_shift;
            
            // console.log(this_day+1);
            // console.log(last_day+1);
            // console.log(day+1);

            if (this_day != last_day) {
                if (week_info[week].classList[0] == "d-none") {
                    week_info.forEach(info_area => {
                        info_area.className = "d-none w-100";
                    });
                    week_info[week].className = "d-table-row w-100";
                }
                daily_info_appear(week, this_day+1);
            } else if (week_info[week].classList[0] == "d-none") {
                week_info[week].className = "d-table-row w-100";
            } else {
                week_info[week].className = "d-none w-100";
            }

            last_day = this_day;
        });
    }
}

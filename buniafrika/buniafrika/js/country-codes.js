const countryCodes = [
    { name: "KE", code: "+254", flag: "🇰🇪" },
    { name: "US", code: "+1", flag: "🇺🇸" },
    { name: "UK", code: "+44", flag: "🇬🇧" },
    { name: "IN", code: "+91", flag: "🇮🇳" },
    { name: "AU", code: "+61", flag: "🇦🇺" },
    { name: "ZA", code: "+27", flag: "🇿🇦" },
    { name: "NG", code: "+234", flag: "🇳🇬" },
    { name: "TZ", code: "+255", flag: "🇹🇿" },
    { name: "UG", code: "+256", flag: "🇺🇬" },
    { name: "DE", code: "+49", flag: "🇩🇪" },
    { name: "FR", code: "+33", flag: "🇫🇷" },
    { name: "JP", code: "+81", flag: "🇯🇵" },
    { name: "CA", code: "+1", flag: "🇨🇦" },
    { name: "CN", code: "+86", flag: "🇨🇳" },
    { name: "BR", code: "+55", flag: "🇧🇷" },
    { name: "MX", code: "+52", flag: "🇲🇽" },
    { name: "AE", code: "+971", flag: "🇦🇪" }
];

document.addEventListener("DOMContentLoaded", () => {
    const selects = document.querySelectorAll(".country-code-select");
    selects.forEach(select => {
        const selectedValue = select.getAttribute("data-selected") || "+254";
        select.innerHTML = ""; 
        countryCodes.forEach(country => {
            const option = document.createElement("option");
            option.value = country.code;
            option.textContent = `${country.flag} ${country.name} (${country.code})`;
            if (country.code === selectedValue) {
                option.selected = true;
            }
            select.appendChild(option);
        });
    });
});

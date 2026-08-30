/**
 * Lightweight Standalone Persian (Jalali) Datepicker
 */
(function() {
    // Jalali Date Conversion Algorithms
    function g2j(gy, gm, gd) {
        var g_d_m = [0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334];
        var jy = (gy <= 1600) ? 0 : 979;
        gy -= (gy <= 1600) ? 621 : 1600;
        var gy2 = (gm > 2) ? (gy + 1) : gy;
        var days = (365 * gy) + (parseInt((gy2 + 3) / 4)) - (parseInt((gy2 + 99) / 100)) + (parseInt((gy2 + 399) / 400)) - 80 + gd + g_d_m[gm - 1];
        jy += 33 * (parseInt(days / 12053));
        days %= 12053;
        jy += 4 * (parseInt(days / 1461));
        days %= 1461;
        jy += parseInt((days - 1) / 365);
        if (days > 0) days = (days - 1) % 365;
        var jm = (days < 186) ? 1 + parseInt(days / 31) : 7 + parseInt((days - 186) / 30);
        var jd = 1 + ((days < 186) ? (days % 31) : ((days - 186) % 30));
        return [jy, jm, jd];
    }

    function j2g(jy, jm, jd) {
        var gy = (jy <= 979) ? 621 : 1600;
        jy -= (jy <= 979) ? 0 : 979;
        var days = (365 * jy) + (parseInt(jy / 33) * 8) + parseInt(((jy % 33) + 3) / 4) + 78 + jd + ((jm < 7) ? (jm - 1) * 31 : ((jm - 7) * 30) + 186);
        gy += 400 * (parseInt(days / 146097));
        days %= 146097;
        if (days > 36524) {
            gy += 100 * (parseInt(--days / 36524));
            days %= 36524;
            if (days >= 365) days++;
        }
        gy += 4 * (parseInt(days / 1461));
        days %= 1461;
        gy += parseInt((days - 1) / 365);
        if (days > 0) days = (days - 1) % 365;
        var gd = days + 1;
        var sal_a = [0, 31, ((gy % 4 === 0 && gy % 100 !== 0) || (gy % 400 === 0)) ? 29 : 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
        var gm;
        for (gm = 0; gm < 13; gm++) {
            var v = sal_a[gm];
            if (gd <= v) break;
            gd -= v;
        }
        return [gy, gm, gd];
    }

    function getJalaliMonthDays(jy, jm) {
        if (jm <= 6) return 31;
        if (jm <= 11) return 30;
        // Leap year check for Jalali
        var a = jy % 33;
        var isLeap = (a === 1 || a === 5 || a === 9 || a === 13 || a === 17 || a === 22 || a === 26 || a === 30);
        return isLeap ? 30 : 29;
    }

    const persianMonths = [
        'فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور',
        'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند'
    ];

    function toPersianDigits(n) {
        const p = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
        return String(n).replace(/\d/g, x => p[x]);
    }

    class PersianDatePicker {
        constructor(input) {
            this.input = input;
            const now = new Date();
            const todayJ = g2j(now.getFullYear(), now.getMonth() + 1, now.getDate());
            this.todayJ = todayJ;
            this.viewYear = todayJ[0];
            this.viewMonth = todayJ[1];
            this.selectedDate = null;

            this.init();
        }

        init() {
            // Read min attribute if exists, default to today's ISO date string
            const now = new Date();
            const todayGStr = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2,'0')}-${String(now.getDate()).padStart(2,'0')}`;
            this.minDateG = this.input.getAttribute('min') || todayGStr;

            // Make text input readOnly to force picker
            this.input.setAttribute('readonly', 'readonly');
            this.input.style.cursor = 'pointer';

            // Create container
            this.container = document.createElement('div');
            this.container.className = 'pdp-container';
            document.body.appendChild(this.container);

            this.render();

            // Event Listeners
            this.input.addEventListener('click', (e) => {
                e.stopPropagation();
                this.show();
            });

            document.addEventListener('click', (e) => {
                if (!this.container.contains(e.target) && e.target !== this.input) {
                    this.hide();
                }
            });

            window.addEventListener('resize', () => this.position());
        }

        position() {
            const rect = this.input.getBoundingClientRect();
            this.container.style.top = (rect.bottom + window.scrollY + 6) + 'px';
            this.container.style.left = (rect.left + window.scrollX) + 'px';
        }

        show() {
            // Close other pickers
            document.querySelectorAll('.pdp-container').forEach(c => c.classList.remove('active'));
            this.position();
            this.container.classList.add('active');
        }

        hide() {
            this.container.classList.remove('active');
        }

        render() {
            this.container.innerHTML = '';

            // Header
            const header = document.createElement('div');
            header.className = 'pdp-header';

            const prevBtn = document.createElement('button');
            prevBtn.className = 'pdp-nav-btn';
            prevBtn.type = 'button';
            prevBtn.innerHTML = '‹';
            prevBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                this.viewMonth--;
                if (this.viewMonth < 1) {
                    this.viewMonth = 12;
                    this.viewYear--;
                }
                this.render();
            });

            const nextBtn = document.createElement('button');
            nextBtn.className = 'pdp-nav-btn';
            nextBtn.type = 'button';
            nextBtn.innerHTML = '›';
            nextBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                this.viewMonth++;
                if (this.viewMonth > 12) {
                    this.viewMonth = 1;
                    this.viewYear++;
                }
                this.render();
            });

            const title = document.createElement('div');
            title.className = 'pdp-title';
            title.textContent = `${persianMonths[this.viewMonth - 1]} ${toPersianDigits(this.viewYear)}`;

            header.appendChild(prevBtn);
            header.appendChild(title);
            header.appendChild(nextBtn);
            this.container.appendChild(header);

            // Weekdays
            const weekdays = document.createElement('div');
            weekdays.className = 'pdp-weekdays';
            ['ش', 'ی', 'د', 'س', 'چ', 'پ', 'ج'].forEach(w => {
                const el = document.createElement('div');
                el.textContent = w;
                weekdays.appendChild(el);
            });
            this.container.appendChild(weekdays);

            // Days Grid
            const daysGrid = document.createElement('div');
            daysGrid.className = 'pdp-days';

            // Calculate starting weekday for first day of month
            const firstG = j2g(this.viewYear, this.viewMonth, 1);
            const firstDateObj = new Date(firstG[0], firstG[1] - 1, firstG[2]);
            // Saturday is 0 in Jalali, Date.getDay(): Sun=0, Mon=1, Tue=2, Wed=3, Thu=4, Fri=5, Sat=6
            let startDayIdx = (firstDateObj.getDay() + 1) % 7;

            for (let i = 0; i < startDayIdx; i++) {
                const empty = document.createElement('div');
                empty.className = 'pdp-day empty';
                daysGrid.appendChild(empty);
            }

            const totalDays = getJalaliMonthDays(this.viewYear, this.viewMonth);
            for (let d = 1; d <= totalDays; d++) {
                const dayEl = document.createElement('div');
                dayEl.className = 'pdp-day';
                dayEl.textContent = toPersianDigits(d);

                // Compare with Jalali today date directly to guarantee zero timezone or algorithm offset issues
                const isPastJalali = (this.viewYear < this.todayJ[0]) ||
                                     (this.viewYear === this.todayJ[0] && this.viewMonth < this.todayJ[1]) ||
                                     (this.viewYear === this.todayJ[0] && this.viewMonth === this.todayJ[1] && d < this.todayJ[2]);

                const curG = j2g(this.viewYear, this.viewMonth, d);
                const curGStr = `${curG[0]}-${String(curG[1]).padStart(2,'0')}-${String(curG[2]).padStart(2,'0')}`;

                if (isPastJalali || (this.minDateG && curGStr < this.minDateG)) {
                    dayEl.classList.add('disabled');
                } else {
                    if (this.todayJ[0] === this.viewYear && this.todayJ[1] === this.viewMonth && this.todayJ[2] === d) {
                        dayEl.classList.add('today');
                    }
                    if (this.selectedDate && this.selectedDate[0] === this.viewYear && this.selectedDate[1] === this.viewMonth && this.selectedDate[2] === d) {
                        dayEl.classList.add('selected');
                    }

                    dayEl.addEventListener('click', (e) => {
                        e.stopPropagation();
                        this.selectedDate = [this.viewYear, this.viewMonth, d];
                        // Set formatted Persian value to input
                        const formattedJ = `${this.viewYear}/${String(this.viewMonth).padStart(2,'0')}/${String(d).padStart(2,'0')}`;
                        this.input.value = formattedJ;

                        // Also set hidden ISO gregorian date if needed
                        let hidden = this.input.parentElement.querySelector(`input[name="${this.input.name}_iso"]`);
                        if (!hidden) {
                            hidden = document.createElement('input');
                            hidden.type = 'hidden';
                            hidden.name = `${this.input.name}_iso`;
                            this.input.parentElement.appendChild(hidden);
                        }
                        hidden.value = curGStr;

                        this.hide();
                    });
                }

                daysGrid.appendChild(dayEl);
            }

            this.container.appendChild(daysGrid);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const dateInputs = document.querySelectorAll('input.persian-datepicker, input[type="text"].jalali-datepicker');
        dateInputs.forEach(input => new PersianDatePicker(input));
    });

    window.PersianDatePicker = PersianDatePicker;
})();

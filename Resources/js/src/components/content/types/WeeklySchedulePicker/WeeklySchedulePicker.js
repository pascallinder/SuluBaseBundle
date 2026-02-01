import React from "react";
import "./weeklySchedulePicker.scss";
import {action} from "mobx";


class WeeklySchedulePicker extends React.PureComponent {
    static defaultProps = {
        value: {'mon':["08:00", "16:00"],
                'tue':["08:00", "16:00"],
                'wed':["08:00", "16:00"],
                'thu':["08:00", "16:00"],
                'fri':["08:00", "16:00"],
                'sat':null,
                'sun':null},
        onChange: () => {}
    };


    days = ["mon", "tue", "wed", "thu", "fri", "sat", "sun"];


    handleTimeChange = action((day, index, newValue) => {
        const { value, onChange } = this.props;
        const updated = { ...value };


        if (!updated[day]) updated[day] = ["", ""];
        updated[day][index] = newValue;


        onChange(updated);
    });


    handleToggleDay = action((day) => {
        const { value, onChange } = this.props;
        const updated = { ...value };


        updated[day] = updated[day] ? null : ["08:00", "16:00"];
        onChange(updated);
    });


    render() {
        const { value = {} } = this.props;


        return (
            <div className="weekly-schedule-picker">
                {this.days.map((day) => {
                    const range = value[day];
                    const enabled = Boolean(range);


                    return (
                        <div key={day} className={`day-row ${enabled ? "enabled" : "disabled"}`}>
                            <label className="day-label">
                                <input
                                    type="checkbox"
                                    checked={enabled}
                                    onChange={() => this.handleToggleDay(day)}
                                />
                                <span>{day.toUpperCase()}</span>
                            </label>


                            {enabled && (
                                <div className="time-inputs">
                                    <input
                                        type="time"
                                        value={range?.[0] ?? ""}
                                        onChange={(e) => this.handleTimeChange(day, 0, e.target.value)}
                                    />
                                    <span className="dash">–</span>
                                    <input
                                        type="time"
                                        value={range?.[1] ?? ""}
                                        onChange={(e) => this.handleTimeChange(day, 1, e.target.value)}
                                    />
                                </div>
                            )}
                        </div>
                    );
                })}
            </div>
        );
    }
}


export default WeeklySchedulePicker;
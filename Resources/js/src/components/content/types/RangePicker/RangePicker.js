import React from "react";
import { Range, getTrackBackground } from "react-range";
import './rangePicker.scss';

const STEP = 0.01;
const MIN = 0;
const MAX = 100;

class RangePicker extends React.PureComponent {
    constructor(props) {
        super(props);
        this.handleChange = this.handleChange.bind(this);
        this.getDefaultValue = this.getDefaultValue.bind(this);
    }

    componentDidMount() {
        const div = document.querySelectorAll('div.range-container');
        for (let i = 0; i < div.length; i++) {
            const rangeCont = div[i].parentElement.parentElement.parentElement;
            rangeCont.classList.add("range-label");
            const fieldCont = div[i].parentElement.parentElement;
            fieldCont.classList.add("range-field-container");
        }

        if (this.props.value === undefined) {

            this.handleChange(this.getDefaultValue());
        }
    }

    getDefaultValue() {
        const min = this.getMin();
        return this.props.schemaOptions?.default?.value !== undefined
            ? parseFloat(this.props.schemaOptions.default.value)
            : min;
    }

    getMin() {
        return this.props.schemaOptions?.min?.value !== undefined
            ? parseFloat(this.props.schemaOptions.min.value)
            : MIN;
    }

    getMax() {
        return this.props.schemaOptions?.max?.value !== undefined
            ? parseFloat(this.props.schemaOptions.max.value)
            : MAX;
    }

    getStep() {
        return this.props.schemaOptions?.step?.value !== undefined
            ? parseFloat(this.props.schemaOptions.step.value)
            : STEP;
    }

    handleChange(value) {
        value = parseFloat(value);
        this.props.onChange(value);
        this.props.onFinish();
    }

    render() {
        const min = this.getMin();
        const max = this.getMax();
        const step = this.getStep();
        const defaultValue = this.getDefaultValue();
        const value = this.props.value !== undefined ? parseFloat(this.props.value) : defaultValue;

        return (
            <div className="range-container">
                <Range
                    values={[value]}
                    step={step}
                    min={min}
                    max={max}
                    onChange={(values) => this.handleChange(values[0])}
                    renderTrack={({ props, children }) => (
                        <div
                            onMouseDown={props.onMouseDown}
                            onTouchStart={props.onTouchStart}
                            style={{
                                ...props.style,
                                height: "36px",
                                display: "flex",
                                width: "100%",
                            }}
                        >
                            <div
                                ref={props.ref}
                                style={{
                                    height: "5px",
                                    width: "100%",
                                    borderRadius: "4px",
                                    background: getTrackBackground({
                                        values: [value],
                                        colors: ["#9676BB", "#ccc"],
                                        min: min,
                                        max: max,
                                    }),
                                    alignSelf: "center",
                                }}
                            >
                                {children}
                            </div>
                        </div>
                    )}
                    renderThumb={({ props, isDragged }) => (
                        <div
                            {...props}
                            style={{
                                ...props.style,
                                height: "20px",
                                width: "20px",
                                borderRadius: "50%",
                                backgroundColor: "#FFF",
                                display: "flex",
                                justifyContent: "center",
                                alignItems: "center",
                                boxShadow: "0px 2px 6px #AAA",
                            }}
                        >
                            <div
                                style={{
                                    height: "100%",
                                    width: "100%",
                                    borderRadius: "50px",
                                    backgroundColor: isDragged ? "#548BF4" : "#CCC",
                                }}
                            />
                        </div>
                    )}
                />
                <div className="input-row">
                    <input
                        type="number"
                        className="slider-input"
                        value={value}
                        min={min}
                        max={max}
                        step={step}
                        onChange={(e) => this.handleChange(e.target.value)}
                    />
                    <button
                        type="button"
                        className="reset-button"
                        onClick={() => this.handleChange(defaultValue)}
                    >
                        Default
                    </button>
                </div>
            </div>
        );
    }
}
RangePicker.defaultProps = {
    value: undefined
};
export default RangePicker;
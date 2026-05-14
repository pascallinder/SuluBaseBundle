import React from 'react';
import { RadioGroup, Radio } from 'sulu-admin-bundle/components/Radio';
import './colorPickerCustom.scss';

class ColorPickerCustom extends React.PureComponent {
    constructor() {
        super();
        this.handleChange = this.handleChange.bind(this);
        this.handleModeChange = this.handleModeChange.bind(this);
        this.state = {
            activeMode: 'light',
        };
        if (process.env.COLOR_PICKER_COLORS) {

            this.colorlist = process.env.COLOR_PICKER_COLORS.split(' ');
            this.colorlist = ['inherit', ...this.colorlist];
        } else {
            this.colorlist = [];
        }
    }

    getNormalizedValue() {
        const { value } = this.props;

        if (value && typeof value === 'object' && !Array.isArray(value)) {
            return {
                light: value.light || 'inherit',
                dark: value.dark ?? null,
            };
        }

        if (typeof value === 'string' && value.length > 0) {
            return {
                light: value,
                dark: null,
            };
        }

        return {
            light: 'inherit',
            dark: null,
        };
    }

    getDisplayedValue(mode) {
        const value = this.getNormalizedValue();

        if (mode === 'dark') {
            return value.dark ?? value.light;
        }

        return value.light;
    }

    handleChange(mode, value) {
        if (this.props.disabled) {
            return;
        }

        const nextValue = this.getNormalizedValue();
        nextValue[mode] = value;

        this.props.onChange(nextValue);
        this.props.onFinish();
    }

    handleModeChange(mode) {
        this.setState({
            activeMode: mode,
        });
    }

    componentDidMount() {
        this.decorateRadioGroups();
    }

    componentDidUpdate() {
        this.decorateRadioGroups();
    }

    decorateRadioGroups() {
        const radiogroups = document.getElementsByClassName('color-picker-radiogroup');
        for (let i = 0; i < radiogroups.length; i++) {
            const labels = radiogroups[i].children;
            radiogroups[i].parentElement.parentElement.parentElement.classList.add('radio-container');
            for (let j = 0; j < labels.length; j++) {
                const element = labels[j].children[0];
                if (!element) {
                    continue;
                }

                element.style.backgroundColor = this.colorlist[j];
                if (this.colorlist[j] === 'inherit') {
                    element.classList.add('inherited-color');
                } else {
                    element.classList.remove('inherited-color');
                }
            }
        }
    }

    render() {
        const { disabled } = this.props;
        const { activeMode } = this.state;
        return (
            <div className={`color-picker-custom${disabled ? ' is-disabled' : ''}`}>
                <div className="color-picker-custom__toolbar">
                    <div className="color-picker-custom__switch" role="tablist" aria-label="Theme mode">
                        <button
                            type="button"
                            className={`color-picker-custom__switch-button${activeMode === 'light' ? ' is-active' : ''}`}
                            onClick={() => this.handleModeChange('light')}
                        >
                            Light
                        </button>
                        <button
                            type="button"
                            className={`color-picker-custom__switch-button${activeMode === 'dark' ? ' is-active' : ''}`}
                            onClick={() => this.handleModeChange('dark')}
                        >
                            Dark
                        </button>
                    </div>
                </div>
                <RadioGroup
                    value={this.getDisplayedValue(activeMode)}
                    onChange={(value) => this.handleChange(activeMode, value)}
                    className="color-picker-radiogroup"
                >
                    {this.colorlist.map((color, index) => (
                        <Radio value={color} key={`${activeMode}-${index}`} />
                    ))}
                </RadioGroup>
            </div>
        );
    }
}
ColorPickerCustom.defaultProps = {
    value: {
        light: 'inherit',
        dark: null,
    },
};
export default ColorPickerCustom;

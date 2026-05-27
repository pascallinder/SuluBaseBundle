import React from 'react';
import { RadioGroup, Radio } from 'sulu-admin-bundle/components/Radio';
import '../ColorPickerCustom/colorPickerCustom.scss';

class ColorPickerSimple extends React.PureComponent {
    constructor() {
        super();
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
            return value.light || value.dark || 'inherit';
        }

        if (typeof value === 'string' && value.length > 0) {
            return value;
        }

        return 'inherit';
    }

    handleChange(value) {
        if (this.props.disabled) {
            return;
        }

        this.props.onChange(value);
        this.props.onFinish();
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

        return (
            <div className={`color-picker-simple${disabled ? ' is-disabled' : ''}`}>
                <RadioGroup
                    value={this.getNormalizedValue()}
                    onChange={(value) => this.handleChange(value)}
                    className="color-picker-radiogroup"
                >
                    {this.colorlist.map((color, index) => (
                        <Radio value={color} key={index} />
                    ))}
                </RadioGroup>
            </div>
        );
    }
}

ColorPickerSimple.defaultProps = {
    value: 'inherit',
};

export default ColorPickerSimple;

import React from 'react';
import { RadioGroup, Radio } from 'sulu-admin-bundle/components/Radio';
import './colorPickerCustom.scss';

class ColorPickerCustom extends React.PureComponent {
    constructor() {
        super();
        this.handleChange = this.handleChange.bind(this);
        if (process.env.COLOR_PICKER_COLORS) {

            this.colorlist = process.env.COLOR_PICKER_COLORS.split(' ');
            this.colorlist = ['inherit', ...this.colorlist];
        } else {
            this.colorlist = [];
        }
    }

    handleChange(value) {
        if (this.props.disabled) {
            return;
        }
        this.props.onChange(value);
        this.props.onFinish();
    }

    componentDidMount() {
        const radiogroup = document.getElementsByClassName("color-picker-radiogroup");
        const radioArray = [];
        for (let i = 0; i < radiogroup.length; i++) {
            let label = radiogroup[i].children;
            radioArray.push(label);
            radiogroup[i].parentElement.parentElement.parentElement.classList.add("radio-container")
            for (let j = 0; j < radioArray[i].length; j++) {
                const element = radioArray[i][j].children[0];
                element.style.backgroundColor = this.colorlist[j]
                if(this.colorlist[j] === 'inherit') {
                    element.classList.add('inherited-color');
                }
            }
        }
    }

    render() {
        const { value: value, disabled } = this.props;
        return (
            <RadioGroup
                value={value}
                onChange={this.handleChange}
                className={`color-picker-radiogroup${disabled ? ' is-disabled' : ''}`}
            >
                {this.colorlist.map((color, index) => (
                    <Radio value={color} key={index} />
                ))}
            </RadioGroup>
        );
    }
}
ColorPickerCustom.defaultProps = {
    value: 'inherit',
};
export default ColorPickerCustom;

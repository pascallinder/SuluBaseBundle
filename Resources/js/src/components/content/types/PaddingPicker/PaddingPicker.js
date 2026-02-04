import React from 'react';
import './paddingPicker.scss';
const defaultPaddingY = 10;
const defaultPaddingX = 25;
function normalizePadding(value) {
    const v = (value || '').trim();
    if (!v) {
        return {
            top: defaultPaddingY.toString(),
            right: defaultPaddingX.toString(),
            bottom: defaultPaddingY.toString(),
            left: defaultPaddingX.toString(),
        };
    }

    const parts = value
        .trim()
        .split(/\s+/)
        .map((p) => p.replace(/px$/, ''));

    if (parts.length === 1) {
        const [a] = parts;
        return { top: a, right: a, bottom: a, left: a };
    }
    if (parts.length === 2) {
        const [a, b] = parts;
        return { top: a, right: b, bottom: a, left: b };
    }
    if (parts.length === 3) {
        const [a, b, c] = parts;
        return { top: a, right: b, bottom: c, left: b };
    }

    const [top, right, bottom, left] = parts;
    return { top, right, bottom, left };
}

function serializePadding({ top, right, bottom, left }) {
    const toPx = (v) => (v === '' ? '0px' : `${v}px`);

    return [
        toPx(top),
        toPx(right),
        toPx(bottom),
        toPx(left),
    ].join(' ');
}

class PaddingPicker extends React.PureComponent {
    constructor(props) {
        super(props);

        this.state = {
            ...normalizePadding(props.value),
            applyToAll: false
        };

        this.handleFieldChange = this.handleFieldChange.bind(this);
        this.finish = this.finish.bind(this);
        this.props.onChange(serializePadding(this.state));
    }

    componentDidUpdate(prevProps) {
        // If Sulu updates the value externally, reflect it
        if (prevProps.value !== this.props.value) {
            this.setState(normalizePadding(this.props.value));
        }
    }
    toggleApplyToAll(enabled) {
        if (enabled) {
            const value = this.state.top || this.state.right || this.state.bottom || this.state.left || defaultPaddingY;
            this.setState({
                applyToAll: true,
                top: value,
                right: value,
                bottom: value,
                left: value,
            }, () => {
                this.props.onChange(serializePadding(this.state));
            });
        } else {
            this.setState({ applyToAll: false });
        }
    }
    handleFieldChange(side, newValue) {
        if (this.state.applyToAll) {
            this.setState({
                top: newValue,
                right: newValue,
                bottom: newValue,
                left: newValue,
            }, () => {
                this.props.onChange(serializePadding(this.state));
            });
            return;
        }

        this.setState(
            { [side]: newValue },
            () => this.props.onChange(serializePadding(this.state))
        );
    }


    finish() {
        // Ensure we push the serialized version on finish too (normalized)
        this.props.onChange(serializePadding(this.state));
        this.props.onFinish();
    }

    render() {
        const { top, right, bottom, left } = this.state;

        return (
            <div className="padding-picker" onBlur={(e) => {
                // finish when focus leaves the whole component
                if (!e.currentTarget.contains(e.relatedTarget)) {
                    this.finish();
                }
            }}>
                <div className="padding-grid">
                    <div className="padding-apply-all">
                        <button
                            type="button"
                            className={this.state.applyToAll ? 'apply-all active' : 'apply-all'}
                            title={this.state.applyToAll ? 'Apply to all sides' : 'Edit sides independently'}
                            onClick={() => this.toggleApplyToAll(!this.state.applyToAll)}
                        />
                    </div>
                    <div className="cell cell-top">
                        <label className="label" htmlFor="padding-top" title="Top">↑</label>
                        <input
                            type="number"
                            min="0"
                            step="1"
                            id="padding-top"
                            className="input"
                            value={top}
                            onChange={(e) => this.handleFieldChange('top', e.target.value)}
                        />
                    </div>

                    <div className="cell cell-right">
                        <label className="label" htmlFor="padding-right" title="Right">→</label>
                        <input
                            type="number"
                            min="0"
                            step="1"
                            id="padding-right"
                            className="input"
                            value={right}
                            onChange={(e) => this.handleFieldChange('right', e.target.value)}
                        />
                    </div>

                    <div className="cell cell-bottom">
                        <label className="label" htmlFor="padding-bottom" title="Bottom">↓</label>
                        <input
                            type="number"
                            min="0"
                            step="1"
                            id="padding-bottom"
                            className="input"
                            value={bottom}
                            onChange={(e) => this.handleFieldChange('bottom', e.target.value)}
                        />
                    </div>

                    <div className="cell cell-left">
                        <label className="label" htmlFor="padding-left" title="Left">←</label>
                        <input
                            type="number"
                            min="0"
                            step="1"
                            id="padding-left"
                            className="input"
                            value={left}
                            onChange={(e) => this.handleFieldChange('left', e.target.value)}
                        />
                    </div>
                </div>
            </div>
        );
    }
}

PaddingPicker.defaultProps = {
    value: `${defaultPaddingY}px ${defaultPaddingX}px ${defaultPaddingY}px ${defaultPaddingX}px`,
};

export default PaddingPicker;
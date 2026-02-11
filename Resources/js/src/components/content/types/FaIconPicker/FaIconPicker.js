import React from 'react';
import Button from 'sulu-admin-bundle/components/Button';
import Overlay from 'sulu-admin-bundle/components/Overlay';
import Input from 'sulu-admin-bundle/components/Input';
import Icon from 'sulu-admin-bundle/components/Icon';
import {getFaSolidIconNames} from './solid';

class FaIconPicker extends React.PureComponent {
    constructor(props) {
        super(props);

        this.state = {
            open: false,
            query: '',
        };

        this.handleSelect = this.handleSelect.bind(this);
        this.handleClear = this.handleClear.bind(this);
        this.handleQueryChange = this.handleQueryChange.bind(this);
    }

    handleSelect(icon) {
        this.props.onChange(icon);
        this.setState({open: false});
    }

    handleClear() {
        this.props.onChange(undefined);
    }

    handleQueryChange(value) {
        this.setState({query: value});
    }

    getFilteredIcons() {
        const all = getFaSolidIconNames();
        const q = (this.state.query || '').trim().toLowerCase();

        return all.filter((name) => name.includes(q));
    }
    render() {
        const {value} = this.props;
        const {open, query} = this.state;

        return (
            <div>
                <div style={{display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 12}}>
                    <div style={{display:'flex', flexDirection: 'row', alignItems: 'center'}}>
                        <label style={{fontSize: 14}}>Icon:</label>
                        <span style={{
                            width: 32,
                            height: 32,
                            display: 'flex',
                            alignItems: 'center',
                            justifyContent: 'center',
                            fontSize: 18,
                        }}>
                            {value ? (
                                <Icon name={'fa-'+value} />
                            ) : null}
                        </span>
                    </div>

                    <div>
                        <button
                            type="button"
                            onClick={() => this.setState({open: true})}
                            style={{
                                height: 32,
                                width: 32,
                                borderRadius: 6,
                                border: '1px solid rgba(0,0,0,0.15)',
                                background: '#fff',
                                cursor: 'pointer',
                                fontSize: 18,
                            }}
                        >
                            <Icon name={'fa-search'} />
                        </button>
                        <button
                            type="button"
                            disabled={!value}
                            onClick={() => this.handleClear()}
                            style={{
                                height: 32,
                                width: 32,
                                borderRadius: 6,
                                border: '1px solid rgba(0,0,0,0.15)',
                                background: '#fff',
                                cursor: 'pointer',
                                fontSize: 18,
                            }}
                        >
                            <Icon name={'fa-times'} />
                        </button>
                    </div>


                </div>

                {open ? (
                    <Overlay
                        open={open}
                        size={'large'}
                        title="Select icon"
                        onClose={() => this.setState({open: false})}
                    >
                        <div style={{display: 'flex', flexDirection: 'column', gap: 12, padding: "20px"}}>
                            <Input
                                value={query}
                                onChange={this.handleQueryChange}
                                placeholder="Search…"
                            />

                            <div
                                style={{
                                    display: 'grid',
                                    gridTemplateColumns: 'repeat(auto-fill, minmax(44px, 1fr))',
                                    gap: 8,
                                }}
                            >
                                {this.getFilteredIcons().map((icon) => (
                                    <button
                                        key={icon}
                                        type="button"
                                        title={icon}
                                        onClick={() => this.handleSelect(icon)}
                                        style={{
                                            height: 44,
                                            borderRadius: 6,
                                            border: '1px solid rgba(0,0,0,0.15)',
                                            background:
                                                value === icon
                                                    ? 'rgba(0,0,0,0.05)'
                                                    : '#fff',
                                            cursor: 'pointer',
                                            fontSize: 18,
                                        }}
                                    >
                                        <Icon name={'fa-'+icon} />
                                    </button>
                                ))}
                            </div>
                        </div>
                    </Overlay>
                ) : null}
            </div>
        );
    }
}
export default FaIconPicker;
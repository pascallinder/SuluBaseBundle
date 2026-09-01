import React, {useEffect, useState} from 'react';
import {MapContainer, Marker, TileLayer, useMapEvents} from 'react-leaflet';
import leaflet from 'leaflet';
import markerIcon from 'leaflet/dist/images/marker-icon.png';
import markerIconRetina from 'leaflet/dist/images/marker-icon-2x.png';
import markerShadow from 'leaflet/dist/images/marker-shadow.png';
import Button from 'sulu-admin-bundle/components/Button';
import Overlay from 'sulu-admin-bundle/components/Overlay';
import {translate} from 'sulu-admin-bundle/utils';
import './mapPicker.scss';

import 'leaflet/dist/leaflet.css';

delete leaflet.Icon.Default.prototype._getIconUrl;
leaflet.Icon.Default.mergeOptions({
    iconUrl: markerIcon,
    iconRetinaUrl: markerIconRetina,
    shadowUrl: markerShadow,
});

const DEFAULT_CENTER = [46.8182, 8.2275];
const DEFAULT_ZOOM = 8;

function isValidCoordinate(longitude, latitude) {
    return longitude !== null && longitude !== undefined
        && latitude !== null && latitude !== undefined
        && Number.isFinite(Number(longitude))
        && Number.isFinite(Number(latitude))
        && Number(longitude) >= -180 && Number(longitude) <= 180
        && Number(latitude) >= -90 && Number(latitude) <= 90;
}

function normalizeCoordinates(value) {
    if (!value || typeof value !== 'object' || Array.isArray(value)) {
        return null;
    }

    const longitude = value.longitude ?? value.long ?? value.xCoordinate ?? value.x;
    const latitude = value.latitude ?? value.lat ?? value.yCoordinate ?? value.y;

    if (!isValidCoordinate(longitude, latitude)) {
        return null;
    }

    return {
        longitude: Number(longitude),
        latitude: Number(latitude),
    };
}

function formatCoordinate(value) {
    return value === null || value === undefined ? '' : String(value);
}

function CoordinateMap({coordinates, disabled, onSelect}) {
    const map = useMapEvents({
        click: ({latlng}) => {
            if (disabled) {
                return;
            }

            onSelect({
                longitude: latlng.lng,
                latitude: latlng.lat,
            });
        },
    });

    useEffect(() => {
        if (coordinates) {
            map.setView([coordinates.latitude, coordinates.longitude]);
        } else {
            map.setView(DEFAULT_CENTER, DEFAULT_ZOOM);
        }
    }, [coordinates?.latitude, coordinates?.longitude, map]);

    useEffect(() => {
        const invalidateMapSize = () => map.invalidateSize();

        invalidateMapSize();
        const timeout = setTimeout(invalidateMapSize, 350);

        return () => clearTimeout(timeout);
    }, [map]);

    if (!coordinates) {
        return null;
    }

    return (
        <Marker
            draggable={!disabled}
            eventHandlers={{
                dragend: (event) => {
                    const {lat, lng} = event.target.getLatLng();
                    onSelect({longitude: lng, latitude: lat});
                },
            }}
            position={[coordinates.latitude, coordinates.longitude]}
        />
    );
}

function MapPicker({disabled, onChange, onFinish, value}) {
    const coordinates = normalizeCoordinates(value);
    const [open, setOpen] = useState(false);
    const [draft, setDraft] = useState(coordinates);
    const [draftInput, setDraftInput] = useState({
        longitude: formatCoordinate(coordinates?.longitude),
        latitude: formatCoordinate(coordinates?.latitude),
    });

    const handleSelect = (nextCoordinates) => {
        if (disabled) {
            return;
        }

        setDraft(nextCoordinates);
        setDraftInput({
            longitude: formatCoordinate(nextCoordinates.longitude),
            latitude: formatCoordinate(nextCoordinates.latitude),
        });
    };

    const handleCoordinateChange = (name, nextValue) => {
        if (disabled) {
            return;
        }

        const nextInput = {...draftInput, [name]: nextValue};
        setDraftInput(nextInput);

        const longitude = Number(nextInput.longitude);
        const latitude = Number(nextInput.latitude);
        if (nextInput.longitude === '' || nextInput.latitude === ''
            || !isValidCoordinate(longitude, latitude)) {
            setDraft(null);
            return;
        }

        setDraft({longitude, latitude});
    };

    const handleOpen = () => {
        setDraft(coordinates);
        setDraftInput({
            longitude: formatCoordinate(coordinates?.longitude),
            latitude: formatCoordinate(coordinates?.latitude),
        });
        setOpen(true);
    };

    const handleConfirm = () => {
        if (disabled) {
            return;
        }

        onChange(draft);
        onFinish();
        setOpen(false);
    };

    return (
        <div className={`map-picker${disabled ? ' is-disabled' : ''}`}>
            <div className="map-picker__summary">
                <div className="map-picker__summary-values">
                    {coordinates ? (
                        <>
                            <span>
                                {translate('sulu_base.map_picker.longitude')}: {coordinates.longitude}
                            </span>
                            <span>
                                {translate('sulu_base.map_picker.latitude')}: {coordinates.latitude}
                            </span>
                        </>
                    ) : (
                        <span>{translate('sulu_base.map_picker.none')}</span>
                    )}
                </div>
                <Button disabled={disabled} icon="su-map-pin" onClick={handleOpen}>
                    {translate('sulu_base.map_picker.select')}
                </Button>
            </div>
            <Overlay
                confirmDisabled={!draft}
                confirmText={translate('sulu_admin.confirm')}
                onClose={() => setOpen(false)}
                onConfirm={handleConfirm}
                open={open}
                size="large"
                title={translate('sulu_base.map_picker.title')}
            >
                <MapContainer
                    center={draft ? [draft.latitude, draft.longitude] : DEFAULT_CENTER}
                    className="map-picker__map"
                    dragging={!disabled}
                    scrollWheelZoom={!disabled}
                    style={{height: 320, width: '100%'}}
                    zoom={draft ? 15 : DEFAULT_ZOOM}
                >
                    <TileLayer
                        attribution="&copy; Stadia Maps &copy; OpenStreetMap contributors"
                        url="https://tiles.stadiamaps.com/tiles/alidade_smooth/{z}/{x}/{y}{r}.png"
                    />
                    <CoordinateMap
                        coordinates={draft}
                        disabled={disabled}
                        onSelect={handleSelect}
                    />
                </MapContainer>

                <div className="map-picker__controls">
                    <label>
                        <span>{translate('sulu_base.map_picker.longitude')}</span>
                        <input
                            disabled={disabled}
                            inputMode="decimal"
                            max="180"
                            min="-180"
                            onChange={(event) => handleCoordinateChange('longitude', event.target.value)}
                            step="any"
                            type="number"
                            value={draftInput.longitude}
                        />
                    </label>
                    <label>
                        <span>{translate('sulu_base.map_picker.latitude')}</span>
                        <input
                            disabled={disabled}
                            inputMode="decimal"
                            max="90"
                            min="-90"
                            onChange={(event) => handleCoordinateChange('latitude', event.target.value)}
                            step="any"
                            type="number"
                            value={draftInput.latitude}
                        />
                    </label>
                </div>
                <p className="map-picker__hint">
                    {translate('sulu_base.map_picker.hint')}
                </p>
            </Overlay>
        </div>
    );
}

MapPicker.defaultProps = {
    disabled: false,
    onChange: () => {},
    onFinish: () => {},
    value: null,
};

export default MapPicker;

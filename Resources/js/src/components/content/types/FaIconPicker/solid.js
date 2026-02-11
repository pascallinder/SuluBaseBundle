// faIconsSolid.js
import '@fortawesome/fontawesome-free/js/solid';
export function getFaSolidIconNames() {
    if (typeof window === 'undefined') {
        return [];
    }

    const ns = window.___FONT_AWESOME___;
    const fas = ns && ns.styles && ns.styles.fas;

    if (!fas) {
        return [];
    }

    // fas is a map: { "address-book": [w,h,ligatures,unicode,path], ... }
    return Object.keys(fas).sort();
}
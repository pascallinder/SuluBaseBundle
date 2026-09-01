import {fieldRegistry} from 'sulu-admin-bundle/containers'
import ColorPickerCustom from './components/content/types/ColorPickerCustom';
import ColorPickerSimple from './components/content/types/ColorPickerSimple';
import RangePicker from "./components/content/types/RangePicker";
import WeeklySchedulePicker from "./components/content/types/WeeklySchedulePicker";
import PaddingPicker from "./components/content/types/PaddingPicker";
import FaIconPicker from "./components/content/types/FaIconPicker";
import MapPicker from "./components/content/types/MapPicker";
import GeneratedLink from './components/form/GeneratedLink';
import SnackbarToolbarAction from './components/form/toolbar/button/SnackbarToolbarAction';
import {formToolbarActionRegistry} from 'sulu-admin-bundle/views';

fieldRegistry.add('color_picker_simple', ColorPickerSimple);
fieldRegistry.add('color_picker_custom', ColorPickerCustom);
fieldRegistry.add('range_picker', RangePicker);
fieldRegistry.add('weekly_schedule_picker', WeeklySchedulePicker);
fieldRegistry.add('padding_picker', PaddingPicker);
fieldRegistry.add('fa_icon_picker', FaIconPicker);
fieldRegistry.add('map_picker', MapPicker);
fieldRegistry.add('generated_link', GeneratedLink);
formToolbarActionRegistry.add('sulu_base.snackbar', SnackbarToolbarAction);

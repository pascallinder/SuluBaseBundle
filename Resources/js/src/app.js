import { fieldRegistry } from 'sulu-admin-bundle/containers'
import ColorPickerCustom from './components/content/types/ColorPickerCustom';
import ColorPickerSimple from './components/content/types/ColorPickerSimple';
import RangePicker from "./components/content/types/RangePicker";
import WeeklySchedulePicker from "./components/content/types/WeeklySchedulePicker";
import PaddingPicker from "./components/content/types/PaddingPicker";
import FaIconPicker from "./components/content/types/FaIconPicker";

fieldRegistry.add('color_picker_simple', ColorPickerSimple);
fieldRegistry.add('color_picker_custom', ColorPickerCustom);
fieldRegistry.add('range_picker', RangePicker);
fieldRegistry.add('weekly_schedule_picker', WeeklySchedulePicker);
fieldRegistry.add('padding_picker', PaddingPicker);
fieldRegistry.add('fa_icon_picker', FaIconPicker);

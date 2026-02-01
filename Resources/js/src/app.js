import { fieldRegistry } from 'sulu-admin-bundle/containers'
import ColorPickerCustom from './components/content/types/ColorPickerCustom';
import RangePicker from "./components/content/types/RangePicker";
import WeeklySchedulePicker from "./components/content/types/WeeklySchedulePicker";


fieldRegistry.add('color_picker_custom', ColorPickerCustom);
fieldRegistry.add('range_picker', RangePicker);
fieldRegistry.add('weekly_schedule_picker', WeeklySchedulePicker);

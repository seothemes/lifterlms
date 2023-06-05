// WordPress dependencies.
import { SVG, Path } from '@wordpress/primitives';

// FontAwesome table-list solid.
const Icon = () => (
	<SVG className="llms-block-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
		<Path
			d="M0 96C0 60.7 28.7 32 64 32H448c35.3 0 64 28.7 64 64V416c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V96zm64 0v64h64V96H64zm384 0H192v64H448V96zM64 224v64h64V224H64zm384 0H192v64H448V224zM64 352v64h64V352H64zm384 0H192v64H448V352z"
		/>
	</SVG>
);

export default Icon;

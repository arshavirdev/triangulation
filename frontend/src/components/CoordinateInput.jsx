export function CoordinateInput({name, value, coordinates, updateData}) {

	function handleChange(event) {
		if (event.target.value && event.target.value <= 0) {
			alert(`${event.target.value || 'Input data'} is invalid value for this field.`)

			return;
		}

		updateData({
			name: event.target.name,
			value: +event.target.value
		})
	}

	return(
		<div className="inputItem">
			<label
				htmlFor={name}
			>
				{`${name}(${coordinates}) distance:`}
			</label>
			<input
				name={name}
				onChange={handleChange}
				value={value}
				type="number"
			/>
		</div>
	)

}

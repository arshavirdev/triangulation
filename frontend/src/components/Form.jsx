import '../App.css';
import {useState} from "react";
import {CoordinateInput} from "./CoordinateInput";

const initData = {
	pointA: {
		coordinates: '50.110889, 8.682139',
		distance: null
	},
	pointB: {
		coordinates: '39.048111, -77.472806',
		distance: null
	},
	pointC: {
		coordinates: '45.849100, -119.714000',
		distance: null
	}
}
export function Form() {

	const [data, setData] = useState(initData);
	const [isLoading, setIsLoading] = useState(false);
	const [result, setResult] = useState('');
	const [error, setError] = useState('');

	function updateData({name, value}) {
		setData({
			...data,
			[name]: {
				...data[name],
				distance: value
			}
		})
	}

	function getParams() {
		return `?distA=${data.pointA.distance}&distB=${data.pointB.distance}&distC=${data.pointC.distance}`
	}
	async function handleCalculate() {
		if (!data.pointA.distance || !data.pointB.distance || !data.pointC.distance) {
			setError('Please fill all fields');

			return
		}
		const url = 'http://localhost:4000';
		setIsLoading(true);
		setError('');
		try {
		 const response = await fetch(`${url}${getParams()}`);
		 const data = await response.json();
		 if (!response.ok) {
		  setError(data.errorMessage);
		  return;
		 }
		 setResult(data.result)
		} catch (error) {
		 setError(error.message || 'Something Went Wrong');
		} finally {
		 setIsLoading(false);
		}	  
	}

	return(
		<>
			<div className="title">
				Triangulation
			</div>
			{
				Object.keys(data).map(point => (
					<CoordinateInput
						key={point}
						name={point}
						value={data[point].distance}
						coordinates={data[point].coordinates}
						updateData={updateData}
					/>
				))
			}

			<div className="calculateBox">
				{
					isLoading ?
						'Loading ...' :
							<button onClick={handleCalculate}>Calculate</button>
				}
				{
					error && <div className="errorMessage">{error}</div>
				}
			</div>
			<div className="resultBox">
				Desired Coordinate: {result || '>>> Fill the distances and click calculate !!!'}
			</div>
		</>
	)

}

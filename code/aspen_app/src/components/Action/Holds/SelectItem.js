import { FormControl, Select, CheckIcon, Radio } from 'native-base';
import React from 'react';
import _ from 'lodash';
import {getTermFromDictionary} from '../../../translations/TranslationService';

export const SelectItem = (props) => {
	const { id, data, item, setItem, holdType, setHoldType, showModal, holdTypeForFormat, language, url } = props;

	let copies = data.copies;

	return (
		<>
			{holdTypeForFormat === 'either' ? (
					<FormControl>
						<Radio.Group
							name="holdTypeGroup"
							defaultValue={holdType}
							value={holdType}
							onChange={(nextValue) => {
								setHoldType(nextValue);
								setItem('');
							}}
							accessibilityLabel="">
							<Radio value="default" my={1} size="sm">
								{getTermFromDictionary(language, 'first_available')}
							</Radio>
							<Radio value="item" my={1} size="sm">
								{getTermFromDictionary(language, 'specific_item')}
							</Radio>
						</Radio.Group>
					</FormControl>
			) : null}
			{holdType === 'item' ? (
				<FormControl>
					<FormControl.Label>{getTermFromDictionary(language, 'select_item')}</FormControl.Label>
					<Select
						name="itemForHold"
						selectedValue={item}
						minWidth="200"
						accessibilityLabel={getTermFromDictionary(language, 'select_item')}
						_selectedItem={{
							bg: 'tertiary.300',
							endIcon: <CheckIcon size="5" />,
						}}
						mt={1}
						mb={2}
						onValueChange={(itemValue) => setItem(itemValue)}>
						{_.map(Object.keys(copies), function (item, index, array) {
							let copy = copies[item];
							return <Select.Item label={copy.location} value={copy.id} key={copy.id} />;
						})}
					</Select>
				</FormControl>
			) : null}
		</>
	)
}
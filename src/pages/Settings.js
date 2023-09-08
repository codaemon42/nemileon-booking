import { Divider } from 'antd'
import React from 'react'
import SettingsLayout from '../components/settings/SettingsLayout'

const Settings = ({style}) => {

    return (
        <div style={style}>
            <Divider orientation='left'> <h3> Settings Page</h3></Divider>
            <SettingsLayout />
        </div>
    )
}

export default Settings
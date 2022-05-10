<?php
/**
 * Adds Farigola_Weather widget.
 */
class Farigola_Weather_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'farigolaweather_widget', // Base ID
			esc_html__( 'El temps a La Farigola', 'fw_domain' ), // Name
			array( 'description' => esc_html__( 'Widget per la estació meteorologica', 'fw_domain' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		echo $args['before_widget'];//Whatever you want to display before widget

		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}
        //Widget Content Output
		$json = file_get_contents('https://api.weather.com/v2/pws/observations/current?stationId='.$instance['estacio'].'&format=json&units=m&numericPrecision=decimal&apiKey='.$instance['apikey'].'');
		$array = json_decode($json, true, 5);

		$humidity = $array['observations']['0']['humidity'];
		$temp = $array['observations']['0']['metric']['temp'];
		$windspeed = $array['observations']['0']['metric']['windSpeed'];
		$uv = $array['observations']['0']['uv'];
		$windir = $array['observations']['0']['winddir'];
		$pressure = $array['observations']['0']['metric']['pressure'];
		$windGust = $array['observations']['0']['metric']['windGust'];
		$precipRate = $array['observations']['0']['metric']['precipRate'];
		$precipTotal = $array['observations']['0']['metric']['precipTotal'];
		$dewpt = $array['observations']['0']['metric']['dewpt'];
		$obsTimeLocal = $array['observations']['0']['obsTimeLocal'];
		$sec = strtotime($obsTimeLocal);
		$localTime = date("d-m-Y H:i", $sec);
		if ($uv<3):
			$uvindex = '<font color="#27AE60">BAIX</font>';
		elseif($uv<6):
			$uvindex = '<font color="#F1C40F">MODERAT</font>';
		elseif($uv<8):
			$uvindex = '<font color="#E67E22">ALT</font>';
		elseif($uv<11):
			$uvindex = '<font color="#C0392B">MOLT ALT</font>';
		elseif($uv>=11):
			$uvindex = '<font color="#800080">EXTREM</font>';
		endif;

		if ($windir<12):
			$windirhuman = '<b>N</b>&nbsp;<font color="2764AE">Tramuntana</font>';
		elseif($windir<34):
			$windirhuman = '<b>NNE</b>';
		elseif($windir<57):
			$windirhuman = '<b>NE</b>&nbsp;<font color="2764AE">Gregal</font>';
		elseif($windir<79):
			$windirhuman = '<b>ENE</b>';
		elseif($windir<102):
			$windirhuman = '<b>E</b>&nbsp;<font color="2764AE">Llevant</font>';
		elseif($windir<124):
			$windirhuman = '<b>ESE</b>';
		elseif($windir<147):
			$windirhuman = '<b>SE</b>&nbsp;<font color="2764AE">Xaloc</font>';
		elseif($windir<169):
			$windirhuman = '<b>SSE</b>';
		elseif($windir<192):
			$windirhuman = '<b>S</b>&nbsp;<font color="2764AE">Migjorn</font>';
		elseif($windir<214):
			$windirhuman = '<b>SSO</b>';
		elseif($windir<237):
			$windirhuman = '<b>SO</b>&nbsp;<font color="2764AE">Garbí</font>';
		elseif($windir<259):
			$windirhuman = '<b>OSO</b>';
		elseif($windir<282):
			$windirhuman = '<b>O</b>&nbsp;<font color="2764AE">Ponent</font>';
		elseif($windir<304):
			$windirhuman = '<b>ONO</b>';
		elseif($windir<327):
			$windirhuman = '<b>NO</b>&nbsp;<font color="2764AE">Mestral</font>';
		elseif($windir<349):
			$windirhuman = '<b>NNO</b>';
		elseif($windir<=360):
			$windirhuman = '<b>N</b>&nbsp;<font color="2764AE">Tramuntana</font>';
		endif;

		
		echo'
		<div style="background-image: url('.plugin_dir_url( dirname( __FILE__ ) ).'includes/images/logo-farigola.jpg);
		background-repeat: no-repeat;
		background-position: top left;
		background-origin: content-box;
		">
		<br>
		<br>
		<br>
		<div id="temperatura" style="display:'.$instance['temperatura'].'">
  		<b>Temperatura:</b>&nbsp;'.$temp.'°C
		</div>
		<div id="humitat" style="display:'.$instance['humitat'].'">
  		<b>Humitat:</b>&nbsp;'.$humidity.'%&nbsp;HR
  		</div>
		<div id="pressio" style="display:'.$instance['pressio'].'">
  		<b>Pressió:</b>&nbsp;'.$pressure.'&nbsp;hPa
  		</div>
		<div id="velocitatvent" style="display:'.$instance['velocitatvent'].'">
  		<b>Velocitat del vent:</b>&nbsp;'.$windspeed.'&nbsp;Km/h
  		</div>
		<div id="direcciovent" style="display:'.$instance['direcciovent'].'">
  		<b>Direcció del vent:</b>&nbsp;'.$windirhuman.'
  		</div>
		<div id="ratxa" style="display:'.$instance['ratxa'].'">
  		<b>Ratxa de vent:</b>&nbsp;'.$windGust.'&nbsp;Km/h
  		</div>
		<div id="indexuv" style="display:'.$instance['indexuv'].'">
  		<b>Index UV:</b>&nbsp;'.$uvindex.'
  		</div>
		<div id="preciphora" style="display:'.$instance['preciphora'].'">
  		<b>Taxa de precipitació:</b>&nbsp;'.$precipRate.'&nbsp;mm/hr
  		</div>
		<div id="precipavui" style="display:'.$instance['precipavui'].'">
  		<b>Precipitació acumulada:</b>&nbsp;'.$precipTotal.'&nbsp;mm
  		</div>
		<div id="rosada" style="display:'.$instance['rosada'].'">
  		<b>Punt de rosada:</b>&nbsp;'.$dewpt.'°C
  		</div>
		<div id="hora" style="display:'.$instance['hora'].'">
  		<b>Hora de dades:</b>&nbsp;'.$localTime.'
  		</div>
		<div id="WUlink" style="display:'.$instance['WUlink'].'">
		<span style="font-size: small;"><i>Per a més informació i històrics podeu clicar <a href="https://www.wunderground.com/dashboard/pws/'.$instance['estacio'].'">aquí</a></i></span>
		</div>
		</div>
		';

		echo $args['after_widget'];//Whatever you want to display after widget
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : 
		esc_html__( 'El temps a La Farigola', 'fw_domain' );

		$apikey = ! empty( $instance['apikey'] ) ? $instance['apikey'] : 
		esc_html__( '0', 'fw_domain' );

		$estacio = ! empty( $instance['estacio'] ) ? $instance['estacio'] : 
		esc_html__( '0', 'fw_domain' );
		
		$temperatura = ! empty( $instance['temperatura'] ) ? 
		$instance['temperatura'] : esc_html__( '', 'fw_domain' );
		
		$humitat= ! empty( $instance['humitat'] ) ? 
		$instance['humitat'] : esc_html__( '', 'fw_domain' );

		$pressio= ! empty( $instance['pressio'] ) ? 
		$instance['pressio'] : esc_html__( '', 'fw_domain' );
		
		$velocitatvent= ! empty( $instance['velocitatvent'] ) ? 
		$instance['velocitatvent'] : esc_html__( '', 'fw_domain' );

		$direcciovent= ! empty( $instance['direcciovent'] ) ? 
		$instance['direcciovent'] : esc_html__( '', 'fw_domain' );

		$ratxa= ! empty( $instance['ratxa'] ) ? 
		$instance['ratxa'] : esc_html__( '', 'fw_domain' );

		$indexuv= ! empty( $instance['indexuv'] ) ? 
		$instance['indexuv'] : esc_html__( '', 'fw_domain' );

		$preciphora= ! empty( $instance['preciphora'] ) ? 
		$instance['preciphora'] : esc_html__( '', 'fw_domain' );

		$precipavui= ! empty( $instance['precipavui'] ) ? 
		$instance['precipavui'] : esc_html__( '', 'fw_domain' );

		$rosada= ! empty( $instance['rosada'] ) ? 
		$instance['rosada'] : esc_html__( '', 'fw_domain' );

		$hora= ! empty( $instance['hora'] ) ? 
		$instance['hora'] : esc_html__( '', 'fw_domain' );

		$WUlink= ! empty( $instance['WUlink'] ) ? 
		$instance['WUlink'] : esc_html__( '', 'fw_domain' );


		?>
	
        <!-- TITLE -->
		<p>
		    <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
                <?php esc_attr_e( 'Títol:', 'fw_domain' ); ?>
        </label> 
		    <input 
            class="widefat" 
            id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" 
            name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" 
            type="text" 
            value="<?php echo esc_attr( $title ); ?>">
		</p>

		<!-- API KEY -->
		<p>
		    <label for="<?php echo esc_attr( $this->get_field_id( 'apikey' ) ); ?>">
                <?php esc_attr_e( 'API Key:', 'fw_domain' ); ?>
        </label> 
		    <input 
            class="widefat" 
            id="<?php echo esc_attr( $this->get_field_id( 'apikey' ) ); ?>" 
            name="<?php echo esc_attr( $this->get_field_name( 'apikey' ) ); ?>" 
            type="text" 
            value="<?php echo esc_attr( $apikey ); ?>">
		</p>
		
		<!-- ESTACIO -->
				<p>
		    <label for="<?php echo esc_attr( $this->get_field_id( 'estacio' ) ); ?>">
                <?php esc_attr_e( 'ID Estació:', 'fw_domain' ); ?>
        </label> 
		    <input 
            class="widefat" 
            id="<?php echo esc_attr( $this->get_field_id( 'estacio' ) ); ?>" 
            name="<?php echo esc_attr( $this->get_field_name( 'estacio' ) ); ?>" 
            type="text" 
            value="<?php echo esc_attr( $estacio ); ?>">
		</p>

		<!-- TEMPERATURA -->
		<p>
		    <label for="<?php echo esc_attr( $this->get_field_id( 'temperatura' ) ); ?>">
                <?php esc_attr_e( 'Temperatura:', 'fw_domain' ); ?>
        </label> 

		<select 
            class="widefat" 
            id="<?php echo esc_attr( $this->get_field_id( 'temperatura' ) ); ?>" 
            name="<?php echo esc_attr( $this->get_field_name( 'temperatura' ) ); ?>" >
			<option value="" <?php echo ($temperatura == "") ? 'selected' : ''; ?>>
			ON
			</option>
			<option value="none;" <?php echo ($temperatura == "none;") ? 'selected' : ''; ?>>
			OFF
			</option>
		</select>
		</p>

		<!-- HUMITAT -->
				<p>
		    <label for="<?php echo esc_attr( $this->get_field_id( 'humitat' ) ); ?>">
                <?php esc_attr_e( 'Humitat:', 'fw_domain' ); ?>
        </label> 

		<select 
            class="widefat" 
            id="<?php echo esc_attr( $this->get_field_id( 'humitat' ) ); ?>" 
            name="<?php echo esc_attr( $this->get_field_name( 'humitat' ) ); ?>" >
			<option value="" <?php echo ($humitat == "") ? 'selected' : ''; ?>>
			ON
			</option>
			<option value="none;" <?php echo ($humitat == "none;") ? 'selected' : ''; ?>>
			OFF
			</option>
		</select>
		</p>

		<!-- PRESSIO -->
				<p>
		    <label for="<?php echo esc_attr( $this->get_field_id( 'pressio' ) ); ?>">
                <?php esc_attr_e( 'Pressió:', 'fw_domain' ); ?>
        </label> 

		<select 
            class="widefat" 
            id="<?php echo esc_attr( $this->get_field_id( 'pressio' ) ); ?>" 
            name="<?php echo esc_attr( $this->get_field_name( 'pressio' ) ); ?>" >
			<option value="" <?php echo ($pressio == "") ? 'selected' : ''; ?>>
			ON
			</option>
			<option value="none;" <?php echo ($pressio == "none;") ? 'selected' : ''; ?>>
			OFF
			</option>
		</select>
		</p>

		<!-- VELOCITAT DEL VENT -->
				<p>
		    <label for="<?php echo esc_attr( $this->get_field_id( 'velocitatvent' ) ); ?>">
                <?php esc_attr_e( 'Velocitat del vent:', 'fw_domain' ); ?>
        </label> 

		<select 
            class="widefat" 
            id="<?php echo esc_attr( $this->get_field_id( 'velocitatvent' ) ); ?>" 
            name="<?php echo esc_attr( $this->get_field_name( 'velocitatvent' ) ); ?>" >
			<option value="" <?php echo ($velocitatvent == "") ? 'selected' : ''; ?>>
			ON
			</option>
			<option value="none;" <?php echo ($velocitatvent == "none;") ? 'selected' : ''; ?>>
			OFF
			</option>
		</select>
		</p>

		<!-- DIRECCIO DEL VENT -->
				<p>
		    <label for="<?php echo esc_attr( $this->get_field_id( 'direcciovent' ) ); ?>">
                <?php esc_attr_e( 'Direcció del vent:', 'fw_domain' ); ?>
        </label> 

		<select 
            class="widefat" 
            id="<?php echo esc_attr( $this->get_field_id( 'direcciovent' ) ); ?>" 
            name="<?php echo esc_attr( $this->get_field_name( 'direcciovent' ) ); ?>" >
			<option value="" <?php echo ($direcciovent == "") ? 'selected' : ''; ?>>
			ON
			</option>
			<option value="none;" <?php echo ($direcciovent == "none;") ? 'selected' : ''; ?>>
			OFF
			</option>
		</select>
		</p>
		
		<!-- RATXA DE VENT -->
				<p>
		    <label for="<?php echo esc_attr( $this->get_field_id( 'ratxa' ) ); ?>">
                <?php esc_attr_e( 'Ratxa de vent:', 'fw_domain' ); ?>
        </label> 

		<select 
            class="widefat" 
            id="<?php echo esc_attr( $this->get_field_id( 'ratxa' ) ); ?>" 
            name="<?php echo esc_attr( $this->get_field_name( 'ratxa' ) ); ?>" >
			<option value="" <?php echo ($ratxa == "") ? 'selected' : ''; ?>>
			ON
			</option>
			<option value="none;" <?php echo ($ratxa == "none;") ? 'selected' : ''; ?>>
			OFF
			</option>
		</select>
		</p>

		<!-- INDEX UV -->
				<p>
		    <label for="<?php echo esc_attr( $this->get_field_id( 'indexuv' ) ); ?>">
                <?php esc_attr_e( 'Index UV:', 'fw_domain' ); ?>
        </label> 

		<select 
            class="widefat" 
            id="<?php echo esc_attr( $this->get_field_id( 'indexuv' ) ); ?>" 
            name="<?php echo esc_attr( $this->get_field_name( 'indexuv' ) ); ?>" >
			<option value="" <?php echo ($indexuv == "") ? 'selected' : ''; ?>>
			ON
			</option>
			<option value="none;" <?php echo ($indexuv == "none;") ? 'selected' : ''; ?>>
			OFF
			</option>
		</select>
		</p>

		<!-- PRECIPITACIONS ULTIMA HORA -->
				<p>
		    <label for="<?php echo esc_attr( $this->get_field_id( 'preciphora' ) ); ?>">
                <?php esc_attr_e( 'Taxa de precipitació:', 'fw_domain' ); ?>
        </label> 

		<select 
            class="widefat" 
            id="<?php echo esc_attr( $this->get_field_id( 'preciphora' ) ); ?>" 
            name="<?php echo esc_attr( $this->get_field_name( 'preciphora' ) ); ?>" >
			<option value="" <?php echo ($preciphora == "") ? 'selected' : ''; ?>>
			ON
			</option>
			<option value="none;" <?php echo ($preciphora == "none;") ? 'selected' : ''; ?>>
			OFF
			</option>
		</select>
		</p>

		<!-- PRECIPITACIONS AVUI -->
				<p>
		    <label for="<?php echo esc_attr( $this->get_field_id( 'precipavui' ) ); ?>">
                <?php esc_attr_e( 'Precipitació acumulada:', 'fw_domain' ); ?>
        </label> 

		<select 
            class="widefat" 
            id="<?php echo esc_attr( $this->get_field_id( 'precipavui' ) ); ?>" 
            name="<?php echo esc_attr( $this->get_field_name( 'precipavui' ) ); ?>" >
			<option value="" <?php echo ($precipavui == "") ? 'selected' : ''; ?>>
			ON
			</option>
			<option value="none;" <?php echo ($precipavui == "none;") ? 'selected' : ''; ?>>
			OFF
			</option>
		</select>
		</p>

		<!-- PUNT DE ROSADA -->
				<p>
		    <label for="<?php echo esc_attr( $this->get_field_id( 'rosada' ) ); ?>">
                <?php esc_attr_e( 'Punt de rosada:', 'fw_domain' ); ?>
        </label> 

		<select 
            class="widefat" 
            id="<?php echo esc_attr( $this->get_field_id( 'rosada' ) ); ?>" 
            name="<?php echo esc_attr( $this->get_field_name( 'rosada' ) ); ?>" >
			<option value="" <?php echo ($rosada == "") ? 'selected' : ''; ?>>
			ON
			</option>
			<option value="none;" <?php echo ($rosada == "none;") ? 'selected' : ''; ?>>
			OFF
			</option>
		</select>
		</p>

		<!-- HORA -->WUlink
				<p>
		    <label for="<?php echo esc_attr( $this->get_field_id( 'hora' ) ); ?>">
                <?php esc_attr_e( 'Hora de dades:', 'fw_domain' ); ?>
        </label> 

		<select 
            class="widefat" 
            id="<?php echo esc_attr( $this->get_field_id( 'hora' ) ); ?>" 
            name="<?php echo esc_attr( $this->get_field_name( 'hora' ) ); ?>" >
			<option value="" <?php echo ($hora == "") ? 'selected' : ''; ?>>
			ON
			</option>
			<option value="none;" <?php echo ($hora == "none;") ? 'selected' : ''; ?>>
			OFF
			</option>
		</select>
		</p>

		<!-- ENLLAÇ -->
		<p>
		    <label for="<?php echo esc_attr( $this->get_field_id( 'WUlink' ) ); ?>">
                <?php esc_attr_e( 'Enllaç a la estació:', 'fw_domain' ); ?>
        </label> 

		<select 
            class="widefat" 
            id="<?php echo esc_attr( $this->get_field_id( 'WUlink' ) ); ?>" 
            name="<?php echo esc_attr( $this->get_field_name( 'WUlink' ) ); ?>" >
			<option value="" <?php echo ($WUlink == "") ? 'selected' : ''; ?>>
			ON
			</option>
			<option value="none;" <?php echo ($hora == "none;") ? 'selected' : ''; ?>>
			OFF
			</option>
		</select>
		</p>

		<?php 

		
		
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';

		$instance['apikey'] = ( ! empty( $new_instance['apikey'] ) ) ? sanitize_text_field( $new_instance['apikey'] ) : '';

		$instance['estacio'] = ( ! empty( $new_instance['estacio'] ) ) ? sanitize_text_field( $new_instance['estacio'] ) : '';

		$instance['temperatura'] = ( ! empty( $new_instance['temperatura'] ) ) ? sanitize_text_field( $new_instance['temperatura'] ) : '';

		$instance['humitat'] = ( ! empty( $new_instance['humitat'] ) ) ? sanitize_text_field( $new_instance['humitat'] ) : '';

		$instance['pressio'] = ( ! empty( $new_instance['pressio'] ) ) ? sanitize_text_field( $new_instance['pressio'] ) : '';

		$instance['velocitatvent'] = ( ! empty( $new_instance['velocitatvent'] ) ) ? sanitize_text_field( $new_instance['velocitatvent'] ) : '';

		$instance['direcciovent'] = ( ! empty( $new_instance['direcciovent'] ) ) ? sanitize_text_field( $new_instance['direcciovent'] ) : '';

		$instance['ratxa'] = ( ! empty( $new_instance['ratxa'] ) ) ? sanitize_text_field( $new_instance['ratxa'] ) : '';

		$instance['indexuv'] = ( ! empty( $new_instance['indexuv'] ) ) ? sanitize_text_field( $new_instance['indexuv'] ) : '';

		$instance['preciphora'] = ( ! empty( $new_instance['preciphora'] ) ) ? sanitize_text_field( $new_instance['preciphora'] ) : '';

		$instance['precipavui'] = ( ! empty( $new_instance['precipavui'] ) ) ? sanitize_text_field( $new_instance['precipavui'] ) : '';

		$instance['rosada'] = ( ! empty( $new_instance['rosada'] ) ) ? sanitize_text_field( $new_instance['rosada'] ) : '';

		$instance['hora'] = ( ! empty( $new_instance['hora'] ) ) ? sanitize_text_field( $new_instance['hora'] ) : '';

		$instance['WUlink'] = ( ! empty( $new_instance['WUlink'] ) ) ? sanitize_text_field( $new_instance['WUlink'] ) : '';
		
		return $instance;
	}

} // class Foo_Widget
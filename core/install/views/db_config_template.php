<?php echo "<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

\$active_group = 'default';
\$active_record = TRUE;

\$db['default']['hostname'] = '{{hostname}}';
\$db['default']['username'] = '{{username}}';
\$db['default']['password'] = '{{password}}';
\$db['default']['database'] = '{{database}}';
\$db['default']['dbdriver'] = 'mysql';
\$db['default']['dbprefix'] = '{{prefix}}';
\$db['default']['pconnect'] = TRUE;
\$db['default']['db_debug'] = {{db_debug}};
\$db['default']['cache_on'] = FALSE;
\$db['default']['cachedir'] = APPPATH . 'cache';
\$db['default']['char_set'] = 'utf8';
\$db['default']['dbcollat'] = 'utf8_general_ci';
\$db['default']['swap_pre'] = '';
\$db['default']['autoinit'] = TRUE;
\$db['default']['stricton'] = FALSE;";
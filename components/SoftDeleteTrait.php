<?php

namespace app\components;

trait SoftDeleteTrait
{
    /**
     * @return int
     */
    public function delete()
    {
        if ($this->isNewRecord) {
            return 0;
        }
        $this->deleted_at = date('Y-m-d H:i:s');
        try {
            return $this->update(false, ['deleted_at']);
        } catch (\Exception $t) {
            return 0;
        } catch (\Throwable $t) {
            return 0;
        }
    }

    /**
     * @return int
     */
    public function restore()
    {
        if ($this->isNewRecord) {
            return 0;
        }
        $this->deleted_at = null;
        try {
            return $this->update(false, ['deleted_at']);
        } catch (\Exception $t) {
            return 0;
        } catch (\Throwable $t) {
            return 0;
        }
    }
}
